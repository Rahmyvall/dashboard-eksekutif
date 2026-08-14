<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LeaveRequestResource;
use App\Models\LeaveRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class LeaveRequestApiController extends Controller
{
    /**
     * Ambil payload request dengan fallback ke raw JSON untuk klien yang
     * mengirim body tanpa Content-Type yang konsisten.
     */
    private function payload(Request $request): array
    {
        $payload = $request->all();

        if ($payload !== []) {
            return $payload;
        }

        $raw = trim((string) $request->getContent());

        if ($raw === '') {
            return [];
        }

        $decoded = json_decode($raw, true);

        return is_array($decoded) ? $decoded : [];
    }

    public function index(Request $request): JsonResponse
    {
        $validator = Validator::make($request->query(), [
            'search' => ['nullable', 'string', 'max:150'],
            'status' => ['nullable', Rule::in(LeaveRequest::statuses())],
            'leave_type' => ['nullable', 'string', 'max:50'],
            'employee_id' => ['nullable', 'integer', 'exists:employees,id'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Parameter filter tidak valid.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $f = $validator->validated();

        $leaveRequests = LeaveRequest::query()
            ->with(['employee.department', 'employee.position', 'approver'])
            ->search($f['search'] ?? null)
            ->byStatus(isset($f['status']) ? (string) $f['status'] : null)
            ->byLeaveType((string) ($f['leave_type'] ?? ''))
            ->when(
                isset($f['employee_id']),
                fn ($query) => $query->where('employee_id', (int) $f['employee_id'])
            )
            ->dateRange($f['start_date'] ?? null, $f['end_date'] ?? null)
            ->latest('start_date')
            ->latest('id')
            ->paginate((int) ($f['per_page'] ?? 15))
            ->withQueryString();

        return response()->json([
            'success' => true,
            'message' => 'Data pengajuan cuti berhasil diambil.',
            'data' => LeaveRequestResource::collection($leaveRequests->items()),
            'meta' => [
                'current_page' => $leaveRequests->currentPage(),
                'from' => $leaveRequests->firstItem(),
                'last_page' => $leaveRequests->lastPage(),
                'per_page' => $leaveRequests->perPage(),
                'to' => $leaveRequests->lastItem(),
                'total' => $leaveRequests->total(),
            ],
            'links' => [
                'first' => $leaveRequests->url(1),
                'last' => $leaveRequests->url($leaveRequests->lastPage()),
                'prev' => $leaveRequests->previousPageUrl(),
                'next' => $leaveRequests->nextPageUrl(),
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $payload = $this->payload($request);

        $validator = Validator::make($payload, [
            'employee_id' => ['required', 'integer', 'exists:employees,id'],
            'leave_type' => ['required', 'string', 'max:50'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'reason' => ['required', 'string'],
            'attachment' => ['nullable', 'file', 'max:5120', 'mimes:jpg,jpeg,png,pdf'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();
        $data['leave_type'] = strtolower(trim((string) $data['leave_type']));
        $data['reason'] = trim((string) $data['reason']);
        $data['status'] = LeaveRequest::STATUS_PENDING;

        $leaveRequest = DB::transaction(function () use ($request, $data): LeaveRequest {
            $leaveRequest = new LeaveRequest([
                'employee_id' => (int) $data['employee_id'],
                'leave_type' => (string) $data['leave_type'],
                'start_date' => (string) $data['start_date'],
                'end_date' => (string) $data['end_date'],
                'reason' => (string) $data['reason'],
                'status' => LeaveRequest::STATUS_PENDING,
            ]);

            $leaveRequest->syncTotalDays();
            $leaveRequest->save();

            if ($request->hasFile('attachment')) {
                $leaveRequest->attachment_path = $request->file('attachment')->store('leave-requests', 'public');
                $leaveRequest->save();
            }

            return $leaveRequest;
        });

        $leaveRequest->load(['employee.department', 'employee.position', 'approver']);

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan cuti berhasil dibuat.',
            'data' => new LeaveRequestResource($leaveRequest),
        ], 201);
    }

    public function show(LeaveRequest $leaveRequest): JsonResponse
    {
        $leaveRequest->load(['employee.department', 'employee.position', 'approver']);

        return response()->json([
            'success' => true,
            'message' => 'Detail pengajuan cuti berhasil diambil.',
            'data' => new LeaveRequestResource($leaveRequest),
        ]);
    }

    public function update(Request $request, LeaveRequest $leaveRequest): JsonResponse
    {
        if (! $leaveRequest->isPending()) {
            return response()->json([
                'success' => false,
                'message' => 'Pengajuan yang sudah diproses tidak dapat diedit.',
                'errors' => [
                    'status' => ['Pengajuan yang sudah diproses tidak dapat diedit.'],
                ],
            ], 422);
        }

        $payload = $this->payload($request);

        $isPatch = $request->isMethod('PATCH');

        $validator = Validator::make($payload, [
            'employee_id' => [$isPatch ? 'sometimes' : 'required', 'integer', 'exists:employees,id'],
            'leave_type' => [$isPatch ? 'sometimes' : 'required', 'string', 'max:50'],
            'start_date' => [$isPatch ? 'sometimes' : 'required', 'date'],
            'end_date' => [$isPatch ? 'sometimes' : 'required', 'date', 'after_or_equal:start_date'],
            'reason' => [$isPatch ? 'sometimes' : 'required', 'string'],
            'attachment' => ['nullable', 'file', 'max:5120', 'mimes:jpg,jpeg,png,pdf'],
            'remove_attachment' => ['nullable', 'boolean'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        if ($isPatch && $data === [] && ! $request->hasFile('attachment') && ! $request->has('remove_attachment')) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada data yang diperbarui.',
                'errors' => [
                    'payload' => ['Kirim minimal satu field untuk update.'],
                ],
            ], 422);
        }

        $merged = [
            'employee_id' => (int) ($data['employee_id'] ?? $leaveRequest->employee_id),
            'leave_type' => strtolower(trim((string) ($data['leave_type'] ?? $leaveRequest->leave_type))),
            'start_date' => (string) ($data['start_date'] ?? $leaveRequest->start_date?->toDateString()),
            'end_date' => (string) ($data['end_date'] ?? $leaveRequest->end_date?->toDateString()),
            'reason' => trim((string) ($data['reason'] ?? $leaveRequest->reason)),
        ];

        $dateValidator = Validator::make($merged, [
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
        ]);

        if ($dateValidator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $dateValidator->errors(),
            ], 422);
        }

        DB::transaction(function () use ($request, $leaveRequest, $merged): void {
            $leaveRequest->fill([
                'employee_id' => (int) $merged['employee_id'],
                'leave_type' => (string) $merged['leave_type'],
                'start_date' => (string) $merged['start_date'],
                'end_date' => (string) $merged['end_date'],
                'reason' => (string) $merged['reason'],
            ]);

            $leaveRequest->syncTotalDays();
            $leaveRequest->save();

            if ($request->boolean('remove_attachment') && $leaveRequest->attachment_path) {
                Storage::disk('public')->delete($leaveRequest->attachment_path);
                $leaveRequest->update(['attachment_path' => null]);
            }

            if ($request->hasFile('attachment')) {
                if ($leaveRequest->attachment_path) {
                    Storage::disk('public')->delete($leaveRequest->attachment_path);
                }

                $leaveRequest->update([
                    'attachment_path' => $request->file('attachment')->store('leave-requests', 'public'),
                ]);
            }
        });

        $leaveRequest->refresh()->load(['employee.department', 'employee.position', 'approver']);

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan cuti berhasil diperbarui.',
            'data' => new LeaveRequestResource($leaveRequest),
        ]);
    }

    public function approve(LeaveRequest $leaveRequest): JsonResponse
    {
        if (! $leaveRequest->isPending()) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya pengajuan berstatus pending yang dapat disetujui.',
                'errors' => [
                    'status' => ['Hanya pengajuan berstatus pending yang dapat disetujui.'],
                ],
            ], 422);
        }

        $authUserId = Auth::id();

        if ($authUserId !== null) {
            $leaveRequest->approve((int) $authUserId);
        } else {
            $leaveRequest->update([
                'status' => LeaveRequest::STATUS_APPROVED,
                'approved_by' => null,
                'approved_at' => now(),
            ]);
        }

        $leaveRequest->refresh()->load(['employee.department', 'employee.position', 'approver']);

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan cuti berhasil disetujui.',
            'data' => new LeaveRequestResource($leaveRequest),
        ]);
    }

    public function reject(LeaveRequest $leaveRequest): JsonResponse
    {
        if (! $leaveRequest->isPending()) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya pengajuan berstatus pending yang dapat ditolak.',
                'errors' => [
                    'status' => ['Hanya pengajuan berstatus pending yang dapat ditolak.'],
                ],
            ], 422);
        }

        $authUserId = Auth::id();

        if ($authUserId !== null) {
            $leaveRequest->reject((int) $authUserId);
        } else {
            $leaveRequest->update([
                'status' => LeaveRequest::STATUS_REJECTED,
                'approved_by' => null,
                'approved_at' => now(),
            ]);
        }

        $leaveRequest->refresh()->load(['employee.department', 'employee.position', 'approver']);

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan cuti berhasil ditolak.',
            'data' => new LeaveRequestResource($leaveRequest),
        ]);
    }

    public function destroy(LeaveRequest $leaveRequest): JsonResponse
    {
        DB::transaction(function () use ($leaveRequest): void {
            if ($leaveRequest->attachment_path) {
                Storage::disk('public')->delete($leaveRequest->attachment_path);
            }

            $leaveRequest->delete();
        });

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan cuti berhasil dihapus.',
            'data' => null,
        ]);
    }
}

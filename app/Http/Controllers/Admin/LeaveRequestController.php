<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLeaveRequestRequest;
use App\Http\Requests\UpdateLeaveRequestRequest;
use App\Models\Employee;
use App\Models\LeaveRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LeaveRequestController extends Controller
{
    public function mine(Request $request): View
    {
        $validated = $this->validateFilter($request, false);

        $employee = $request->user()?->resolveEmployee();

        if (! $employee) {
            return view('super-admin.leave-requests.index', [
                'leaveRequests' => LeaveRequest::query()->whereRaw('1 = 0')->paginate((int) ($validated['per_page'] ?? 10)),
                'employees' => collect(),
                'statuses' => LeaveRequest::statuses(),
                'leaveTypes' => collect(),
                'filters' => [
                    'search' => (string) ($validated['search'] ?? ''),
                    'status' => (string) ($validated['status'] ?? ''),
                    'leave_type' => (string) ($validated['leave_type'] ?? ''),
                    'start_date' => (string) ($validated['start_date'] ?? ''),
                    'end_date' => (string) ($validated['end_date'] ?? ''),
                    'employee_id' => '',
                    'per_page' => (string) ($validated['per_page'] ?? '10'),
                ],
                'isMineView' => true,
            ]);
        }

        $leaveRequests = LeaveRequest::query()
            ->with(['employee.department', 'employee.position', 'approver'])
            ->byEmployee((int) $employee->id)
            ->search($validated['search'] ?? null)
            ->byStatus($validated['status'] ?? null)
            ->byLeaveType((string) ($validated['leave_type'] ?? ''))
            ->dateRange($validated['start_date'] ?? null, $validated['end_date'] ?? null)
            ->latest('start_date')
            ->latest('id')
            ->paginate((int) ($validated['per_page'] ?? 10))
            ->withQueryString();

        return view('super-admin.leave-requests.index', [
            'leaveRequests' => $leaveRequests,
            'employees' => collect([$employee]),
            'statuses' => LeaveRequest::statuses(),
            'leaveTypes' => LeaveRequest::query()->select('leave_type')->distinct()->orderBy('leave_type')->pluck('leave_type'),
            'filters' => [
                'search' => (string) ($validated['search'] ?? ''),
                'status' => (string) ($validated['status'] ?? ''),
                'leave_type' => (string) ($validated['leave_type'] ?? ''),
                'start_date' => (string) ($validated['start_date'] ?? ''),
                'end_date' => (string) ($validated['end_date'] ?? ''),
                'employee_id' => (string) $employee->id,
                'per_page' => (string) ($validated['per_page'] ?? '10'),
            ],
            'isMineView' => true,
        ]);
    }

    public function index(Request $request): View
    {
        $validated = $this->validateFilter($request, true);

        $leaveRequests = LeaveRequest::query()
            ->with(['employee.department', 'employee.position', 'approver'])
            ->search($validated['search'] ?? null)
            ->byStatus($validated['status'] ?? null)
            ->byLeaveType((string) ($validated['leave_type'] ?? ''))
            ->when(
                filled($validated['employee_id'] ?? null),
                fn($query) => $query->where('employee_id', (int) $validated['employee_id'])
            )
            ->dateRange($validated['start_date'] ?? null, $validated['end_date'] ?? null)
            ->latest('start_date')
            ->latest('id')
            ->paginate((int) ($validated['per_page'] ?? 10))
            ->withQueryString();

        return view('super-admin.leave-requests.index', [
            'leaveRequests' => $leaveRequests,
            'employees' => Employee::query()->orderBy('full_name')->get(['id', 'full_name', 'employee_number']),
            'statuses' => LeaveRequest::statuses(),
            'leaveTypes' => LeaveRequest::query()->select('leave_type')->distinct()->orderBy('leave_type')->pluck('leave_type'),
            'filters' => [
                'search' => (string) ($validated['search'] ?? ''),
                'status' => (string) ($validated['status'] ?? ''),
                'leave_type' => (string) ($validated['leave_type'] ?? ''),
                'start_date' => (string) ($validated['start_date'] ?? ''),
                'end_date' => (string) ($validated['end_date'] ?? ''),
                'employee_id' => (string) ($validated['employee_id'] ?? ''),
                'per_page' => (string) ($validated['per_page'] ?? '10'),
            ],
            'isMineView' => false,
        ]);
    }

    public function create(Request $request): View
    {
        $employee = $request->user()?->resolveEmployee();

        return view('super-admin.leave-requests.create', [
            'leaveRequest' => new LeaveRequest(),
            'employees' => Employee::query()->active()->orderBy('full_name')->get(['id', 'full_name', 'employee_number']),
            'currentEmployeeId' => $employee?->id,
            'statuses' => LeaveRequest::statuses(),
        ]);
    }

    public function store(StoreLeaveRequestRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $authEmployeeId = $request->user()?->resolveEmployee()?->id;

        $employeeId = isset($validated['employee_id'])
            ? (int) $validated['employee_id']
            : ($authEmployeeId ? (int) $authEmployeeId : null);

        if (! $employeeId) {
            return back()->withInput()->withErrors([
                'employee_id' => 'Data pegawai untuk user login tidak ditemukan.',
            ]);
        }

        $leaveRequest = DB::transaction(function () use ($request, $validated, $employeeId): LeaveRequest {
            $leaveRequest = new LeaveRequest([
                'employee_id' => $employeeId,
                'leave_type' => strtolower(trim((string) $validated['leave_type'])),
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'reason' => $validated['reason'],
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

        if ($request->routeIs('leave-requests.*')) {
            return redirect()->route('leave-requests.mine')
                ->with('success', 'Pengajuan cuti berhasil dibuat.');
        }

        return redirect()->route('super-admin.leave-requests.show', $leaveRequest)
            ->with('success', 'Pengajuan cuti berhasil dibuat.');
    }

    public function show(LeaveRequest $leaveRequest): View
    {
        $leaveRequest->load(['employee.department', 'employee.position', 'approver']);

        return view('super-admin.leave-requests.show', compact('leaveRequest'));
    }

    public function edit(LeaveRequest $leaveRequest): View
    {
        $leaveRequest->load(['employee.department', 'employee.position', 'approver']);

        return view('super-admin.leave-requests.edit', [
            'leaveRequest' => $leaveRequest,
            'employees' => Employee::query()->active()->orderBy('full_name')->get(['id', 'full_name', 'employee_number']),
            'statuses' => LeaveRequest::statuses(),
        ]);
    }

    public function update(UpdateLeaveRequestRequest $request, LeaveRequest $leaveRequest): RedirectResponse
    {
        if (! $leaveRequest->isPending()) {
            return back()->withInput()->withErrors([
                'status' => 'Pengajuan yang sudah diproses tidak dapat diedit.',
            ]);
        }

        $validated = $request->validated();

        DB::transaction(function () use ($request, $validated, $leaveRequest): void {
            $leaveRequest->fill([
                'employee_id' => $validated['employee_id'] ?? $leaveRequest->employee_id,
                'leave_type' => strtolower(trim((string) $validated['leave_type'])),
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'reason' => $validated['reason'],
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

        return redirect()->route('super-admin.leave-requests.show', $leaveRequest)
            ->with('success', 'Pengajuan cuti berhasil diperbarui.');
    }

    public function approve(LeaveRequest $leaveRequest): RedirectResponse
    {
        if (! $leaveRequest->isPending()) {
            return back()->withErrors([
                'status' => 'Hanya pengajuan berstatus pending yang dapat disetujui.',
            ]);
        }

        $leaveRequest->approve((int) Auth::id());

        return back()->with('success', 'Pengajuan cuti berhasil disetujui.');
    }

    public function reject(LeaveRequest $leaveRequest): RedirectResponse
    {
        if (! $leaveRequest->isPending()) {
            return back()->withErrors([
                'status' => 'Hanya pengajuan berstatus pending yang dapat ditolak.',
            ]);
        }

        $leaveRequest->reject((int) Auth::id());

        return back()->with('success', 'Pengajuan cuti berhasil ditolak.');
    }

    public function destroy(LeaveRequest $leaveRequest): RedirectResponse
    {
        DB::transaction(function () use ($leaveRequest): void {
            if ($leaveRequest->attachment_path) {
                Storage::disk('public')->delete($leaveRequest->attachment_path);
            }

            $leaveRequest->delete();
        });

        return redirect()->route('super-admin.leave-requests.index')
            ->with('success', 'Pengajuan cuti berhasil dihapus.');
    }

    private function validateFilter(Request $request, bool $allowEmployee): array
    {
        $rules = [
            'search' => ['nullable', 'string', 'max:150'],
            'status' => ['nullable', 'string', 'in:' . implode(',', LeaveRequest::statuses())],
            'leave_type' => ['nullable', 'string', 'max:50'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'per_page' => ['nullable', 'integer', 'in:10,25,50,100'],
        ];

        if ($allowEmployee) {
            $rules['employee_id'] = ['nullable', 'integer', 'exists:employees,id'];
        }

        return $request->validate($rules);
    }
}
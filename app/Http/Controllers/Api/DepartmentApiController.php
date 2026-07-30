<?php

declare (strict_types = 1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\IndexDepartmentRequest;
use App\Http\Requests\Api\StoreDepartmentRequest;
use App\Http\Requests\Api\UpdateDepartmentRequest;
use App\Http\Resources\DepartmentResource;
use App\Models\Department;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class DepartmentApiController extends Controller
{
    /**
     * GET /api/departments
     */
    public function index(
        IndexDepartmentRequest $request
    ): AnonymousResourceCollection {
        $validated = $request->validated();

        $search = trim((string) ($validated['search'] ?? ''));
        $status = $validated['status'] ?? null;

        $allowedSortFields = [
            'id',
            'code',
            'name',
            'status',
            'created_at',
            'updated_at',
        ];

        $requestedSortBy = (string) ($validated['sort_by'] ?? 'name');

        $sortBy = in_array($requestedSortBy, $allowedSortFields, true)
            ? $requestedSortBy
            : 'name';

        $sortDirection = ($validated['sort_direction'] ?? 'asc') === 'desc'
            ? 'desc'
            : 'asc';

        $perPage = min(
            max((int) ($validated['per_page'] ?? 10), 1),
            100
        );

        $departments = Department::query()
            ->when(
                $search !== '',
                function (Builder $query) use ($search): void {
                    $query->where(
                        function (Builder $subQuery) use ($search): void {
                            $subQuery
                                ->where('code', 'like', "%{$search}%")
                                ->orWhere('name', 'like', "%{$search}%")
                                ->orWhere('description', 'like', "%{$search}%");
                        }
                    );
                }
            )
            ->when(
                in_array($status, ['active', 'inactive'], true),
                fn(Builder $query): Builder =>
                $query->where('status', $status)
            )
            ->orderBy($sortBy, $sortDirection)
            ->paginate($perPage)
            ->withQueryString();

        return DepartmentResource::collection($departments)
            ->additional([
                'success' => true,
                'message' => 'Daftar department berhasil diambil.',
            ]);
    }

    /**
     * POST /api/departments
     */
    public function store(
        StoreDepartmentRequest $request
    ): JsonResponse {
        $data = $this->normalizePayload($request->validated());

        $department = DB::transaction(
            fn(): Department => Department::create($data)
        );

        return (new DepartmentResource($department->refresh()))
            ->additional([
                'success' => true,
                'message' => 'Department berhasil ditambahkan.',
            ])
            ->response()
            ->setStatusCode(201);
    }

    /**
     * GET /api/departments/{department}
     */
    public function show(
        Department $department
    ): DepartmentResource {
        return (new DepartmentResource($department))
            ->additional([
                'success' => true,
                'message' => 'Detail department berhasil diambil.',
            ]);
    }

    /**
     * PUT|PATCH /api/departments/{department}
     */
    public function update(
        UpdateDepartmentRequest $request,
        Department $department
    ): DepartmentResource {
        $data = $this->normalizePayload($request->validated());

        DB::transaction(function () use ($department, $data): void {
            $department->update($data);
        });

        return (new DepartmentResource($department->refresh()))
            ->additional([
                'success' => true,
                'message' => 'Department berhasil diperbarui.',
            ]);
    }

    /**
     * PATCH /api/departments/{department}/status
     */
    public function updateStatus(
        Request $request,
        Department $department
    ): DepartmentResource {
        $validated = $request->validate(
            [
                'status' => [
                    'required',
                    Rule::in(['active', 'inactive']),
                ],
            ],
            [
                'status.required' =>
                'Status department wajib diisi.',

                'status.in'       =>
                'Status department harus active atau inactive.',
            ]
        );

        DB::transaction(function () use (
            $department,
            $validated
        ): void {
            $department->update([
                'status' => $validated['status'],
            ]);
        });

        return (new DepartmentResource($department->refresh()))
            ->additional([
                'success' => true,
                'message' => 'Status department berhasil diperbarui.',
            ]);
    }

    /**
     * DELETE /api/departments/{department}
     */
    public function destroy(
        Department $department
    ): JsonResponse {
        $deletedData = [
            'id'   => $department->id,
            'code' => $department->code,
            'name' => $department->name,
        ];

        DB::transaction(function () use ($department): void {
            $department->delete();
        });

        return response()->json([
            'success' => true,
            'message' => 'Department berhasil dipindahkan ke trash.',
            'data'    => [
                 ...$deletedData,
                'is_deleted' => true,
                'deleted_at' => $department->deleted_at?->toISOString(),
            ],
        ]);
    }

    /**
     * GET /api/departments/trash
     */
    public function trash(
        IndexDepartmentRequest $request
    ): AnonymousResourceCollection {
        $validated = $request->validated();

        $search = trim((string) ($validated['search'] ?? ''));
        $status = $validated['status'] ?? null;

        $allowedSortFields = [
            'id',
            'code',
            'name',
            'status',
            'created_at',
            'updated_at',
            'deleted_at',
        ];

        $requestedSortBy = (string) (
            $validated['sort_by'] ?? 'deleted_at'
        );

        $sortBy = in_array($requestedSortBy, $allowedSortFields, true)
            ? $requestedSortBy
            : 'deleted_at';

        $sortDirection = ($validated['sort_direction'] ?? 'desc') === 'asc'
            ? 'asc'
            : 'desc';

        $perPage = min(
            max((int) ($validated['per_page'] ?? 10), 1),
            100
        );

        $departments = Department::onlyTrashed()
            ->when(
                $search !== '',
                function (Builder $query) use ($search): void {
                    $query->where(
                        function (Builder $subQuery) use ($search): void {
                            $subQuery
                                ->where('code', 'like', "%{$search}%")
                                ->orWhere('name', 'like', "%{$search}%")
                                ->orWhere('description', 'like', "%{$search}%");
                        }
                    );
                }
            )
            ->when(
                in_array($status, ['active', 'inactive'], true),
                fn(Builder $query): Builder =>
                $query->where('status', $status)
            )
            ->orderBy($sortBy, $sortDirection)
            ->paginate($perPage)
            ->withQueryString();

        return DepartmentResource::collection($departments)
            ->additional([
                'success' => true,
                'message' =>
                'Daftar trash department berhasil diambil.',
            ]);
    }

    /**
     * PATCH /api/departments/{department}/restore
     */
    public function restore(
        int $department
    ): DepartmentResource {
        $trashedDepartment = Department::onlyTrashed()
            ->findOrFail($department);

        DB::transaction(function () use (
            $trashedDepartment
        ): void {
            $trashedDepartment->restore();
        });

        return (new DepartmentResource(
            $trashedDepartment->refresh()
        ))
            ->additional([
                'success' => true,
                'message' => 'Department berhasil dipulihkan.',
            ]);
    }

    /**
     * DELETE /api/departments/{department}/force-delete
     */
    public function forceDelete(
        int $department
    ): JsonResponse {
        $trashedDepartment = Department::onlyTrashed()
            ->findOrFail($department);

        $deletedData = [
            'id'   => $trashedDepartment->id,
            'code' => $trashedDepartment->code,
            'name' => $trashedDepartment->name,
        ];

        DB::transaction(function () use (
            $trashedDepartment
        ): void {
            $trashedDepartment->forceDelete();
        });

        return response()->json([
            'success' => true,
            'message' =>
            'Department berhasil dihapus secara permanen.',
            'data'    => $deletedData,
        ]);
    }

    /**
     * GET /api/departments/statistics
     */
    public function statistics(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' =>
            'Statistik department berhasil diambil.',

            'data'    => [
                'total'             =>
                Department::query()->count(),

                'active'            =>
                Department::query()
                    ->where('status', 'active')
                    ->count(),

                'inactive'          =>
                Department::query()
                    ->where('status', 'inactive')
                    ->count(),

                'trashed'           =>
                Department::onlyTrashed()->count(),

                'including_trashed' =>
                Department::withTrashed()->count(),
            ],
        ]);
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    private function normalizePayload(array $data): array
    {
        if (array_key_exists('code', $data)) {
            $data['code'] = strtoupper(
                trim((string) $data['code'])
            );
        }

        if (array_key_exists('name', $data)) {
            $data['name'] = trim(
                (string) $data['name']
            );
        }

        if (array_key_exists('description', $data)) {
            $description = trim(
                (string) ($data['description'] ?? '')
            );

            $data['description'] =
            $description !== ''
                ? $description
                : null;
        }

        if (array_key_exists('status', $data)) {
            $data['status'] = strtolower(
                trim((string) $data['status'])
            );
        }

        return $data;
    }
}

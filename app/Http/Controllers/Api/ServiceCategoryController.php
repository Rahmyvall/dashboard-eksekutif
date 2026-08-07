<?php

declare (strict_types = 1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceCategory\IndexServiceCategoryRequest;
use App\Http\Requests\ServiceCategory\StoreServiceCategoryRequest;
use App\Http\Requests\ServiceCategory\UpdateServiceCategoryRequest;
use App\Http\Resources\ServiceCategoryResource;
use App\Models\ServiceCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class ServiceCategoryController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    |
    | GET /api/v1/service-categories
    |
    | Query:
    | - search
    | - status
    | - sort_by
    | - sort_direction
    | - per_page
    | - page
    |
    */

    public function index(
        IndexServiceCategoryRequest $request
    ): JsonResponse {
        try {

            $filters = $request->validated();

            $search = $filters['search'] ?? null;

            $status = $filters['status'] ?? null;

            $sortBy = $filters['sort_by'] ?? 'created_at';

            $sortDirection = $filters['sort_direction'] ?? 'desc';

            $perPage = (int) (
                $filters['per_page'] ?? 15
            );

            /*
            |--------------------------------------------------------------------------
            | QUERY
            |--------------------------------------------------------------------------
            */

            $query = ServiceCategory::query();

            /*
            |--------------------------------------------------------------------------
            | SEARCH
            |--------------------------------------------------------------------------
            */

            if (
                $search !== null
                && trim((string) $search) !== ''
            ) {

                $keyword = mb_strtolower(
                    trim((string) $search)
                );

                $pattern = '%' . $keyword . '%';

                $query->where(
                    function (Builder $builder) use ($pattern): void {

                        $builder
                            ->whereRaw(
                                'LOWER(code) LIKE ?',
                                [$pattern]
                            )
                            ->orWhereRaw(
                                'LOWER(name) LIKE ?',
                                [$pattern]
                            )
                            ->orWhereRaw(
                                "LOWER(COALESCE(description, '')) LIKE ?",
                                [$pattern]
                            );
                    }
                );
            }

            /*
            |--------------------------------------------------------------------------
            | FILTER STATUS
            |--------------------------------------------------------------------------
            */

            if ($status !== null) {

                $query->where(
                    'status',
                    $status
                );
            }

            /*
            |--------------------------------------------------------------------------
            | SORTING + PAGINATION
            |--------------------------------------------------------------------------
            */

            $serviceCategories = $query
                ->orderBy(
                    $sortBy,
                    $sortDirection
                )
                ->paginate($perPage)
                ->withQueryString();

            /*
            |--------------------------------------------------------------------------
            | RESOURCE
            |--------------------------------------------------------------------------
            */

            $data = collect(
                $serviceCategories->items()
            )
                ->map(
                    function (ServiceCategory $serviceCategory) use ($request): array {

                        return (
                            new ServiceCategoryResource(
                                $serviceCategory
                            )
                        )->resolve($request);
                    }
                )
                ->values()
                ->all();

            /*
            |--------------------------------------------------------------------------
            | RESPONSE
            |--------------------------------------------------------------------------
            */

            return response()->json([
                'success' => true,

                'message' =>
                'Data kategori layanan berhasil diambil.',

                'data'    => $data,

                'meta'    => [
                    'current_page' =>
                    $serviceCategories->currentPage(),

                    'from'         =>
                    $serviceCategories->firstItem(),

                    'last_page'    =>
                    $serviceCategories->lastPage(),

                    'per_page'     =>
                    $serviceCategories->perPage(),

                    'to'           =>
                    $serviceCategories->lastItem(),

                    'total'        =>
                    $serviceCategories->total(),
                ],

                'links'   => [
                    'first' =>
                    $serviceCategories->url(1),

                    'last'  =>
                    $serviceCategories->url(
                        $serviceCategories->lastPage()
                    ),

                    'prev'  =>
                    $serviceCategories->previousPageUrl(),

                    'next'  =>
                    $serviceCategories->nextPageUrl(),
                ],
            ]);

        } catch (Throwable $exception) {

            report($exception);

            return response()->json([
                'success' => false,

                'message' =>
                'Terjadi kesalahan saat mengambil data kategori layanan.',
            ], 500);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    |
    | POST /api/v1/service-categories
    |
    | Body:
    |
    | {
    |     "code": "SVC-001",
    |     "name": "Layanan Konsultasi",
    |     "description": "Kategori layanan konsultasi",
    |     "status": "active"
    | }
    |
    */

    public function store(
        StoreServiceCategoryRequest $request
    ): JsonResponse {
        try {

            /*
            |--------------------------------------------------------------------------
            | VALIDATED DATA
            |--------------------------------------------------------------------------
            */

            $validated = $request->validated();

            /*
            |--------------------------------------------------------------------------
            | CREATE
            |--------------------------------------------------------------------------
            */

            $serviceCategory = ServiceCategory::query()
                ->create($validated);

            /*
            |--------------------------------------------------------------------------
            | REFRESH
            |--------------------------------------------------------------------------
            */

            $serviceCategory->refresh();

            /*
            |--------------------------------------------------------------------------
            | RESPONSE
            |--------------------------------------------------------------------------
            */

            return response()->json([
                'success' => true,

                'message' =>
                'Kategori layanan berhasil ditambahkan.',

                'data'    => (
                    new ServiceCategoryResource(
                        $serviceCategory
                    )
                )->resolve($request),

            ], 201);

        } catch (Throwable $exception) {

            report($exception);

            return response()->json([
                'success' => false,

                'message' =>
                'Kategori layanan gagal ditambahkan.',
            ], 500);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    |
    | GET /api/v1/service-categories/{serviceCategory}
    |
    */

    public function show(
        Request $request,
        ServiceCategory $serviceCategory
    ): JsonResponse {

        return response()->json([
            'success' => true,

            'message' =>
            'Detail kategori layanan berhasil diambil.',

            'data'    => (
                new ServiceCategoryResource(
                    $serviceCategory
                )
            )->resolve($request),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    |
    | PUT:
    | /api/v1/service-categories/{serviceCategory}
    |
    | PATCH:
    | /api/v1/service-categories/{serviceCategory}
    |
    */

    public function update(
        UpdateServiceCategoryRequest $request,
        ServiceCategory $serviceCategory
    ): JsonResponse {
        try {

            /*
            |--------------------------------------------------------------------------
            | VALIDATED DATA
            |--------------------------------------------------------------------------
            */

            $validated = $request->validated();

            /*
            |--------------------------------------------------------------------------
            | EMPTY PATCH
            |--------------------------------------------------------------------------
            */

            if (
                $request->isMethod('PATCH')
                && $validated === []
            ) {

                return response()->json([
                    'success' => false,

                    'message' =>
                    'Tidak ada data yang dikirim untuk diperbarui.',

                    'errors'  => [
                        'payload' => [
                            'Kirim minimal satu field untuk diperbarui.',
                        ],
                    ],
                ], 422);
            }

            /*
            |--------------------------------------------------------------------------
            | UPDATE
            |--------------------------------------------------------------------------
            */

            $serviceCategory->update(
                $validated
            );

            /*
            |--------------------------------------------------------------------------
            | REFRESH
            |--------------------------------------------------------------------------
            */

            $serviceCategory->refresh();

            /*
            |--------------------------------------------------------------------------
            | RESPONSE
            |--------------------------------------------------------------------------
            */

            return response()->json([
                'success' => true,

                'message' =>
                'Kategori layanan berhasil diperbarui.',

                'data'    => (
                    new ServiceCategoryResource(
                        $serviceCategory
                    )
                )->resolve($request),
            ]);

        } catch (Throwable $exception) {

            report($exception);

            return response()->json([
                'success' => false,

                'message' =>
                'Kategori layanan gagal diperbarui.',
            ], 500);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | TOGGLE STATUS
    |--------------------------------------------------------------------------
    |
    | PATCH
    | /api/v1/service-categories/{serviceCategory}/toggle-status
    |
    */

    public function toggleStatus(
        Request $request,
        ServiceCategory $serviceCategory
    ): JsonResponse {
        try {

            /*
            |--------------------------------------------------------------------------
            | NEW STATUS
            |--------------------------------------------------------------------------
            */

            $newStatus =
            $serviceCategory->status
            === ServiceCategory::STATUS_ACTIVE

                ? ServiceCategory::STATUS_INACTIVE

                : ServiceCategory::STATUS_ACTIVE;

            /*
            |--------------------------------------------------------------------------
            | UPDATE
            |--------------------------------------------------------------------------
            */

            $serviceCategory->update([
                'status' => $newStatus,
            ]);

            $serviceCategory->refresh();

            /*
            |--------------------------------------------------------------------------
            | MESSAGE
            |--------------------------------------------------------------------------
            */

            $message =
            $newStatus === ServiceCategory::STATUS_ACTIVE

                ? 'Status kategori layanan berhasil diubah menjadi aktif.'

                : 'Status kategori layanan berhasil diubah menjadi tidak aktif.';

            /*
            |--------------------------------------------------------------------------
            | RESPONSE
            |--------------------------------------------------------------------------
            */

            return response()->json([
                'success' => true,

                'message' => $message,

                'data'    => (
                    new ServiceCategoryResource(
                        $serviceCategory
                    )
                )->resolve($request),
            ]);

        } catch (Throwable $exception) {

            report($exception);

            return response()->json([
                'success' => false,

                'message' =>
                'Status kategori layanan gagal diperbarui.',
            ], 500);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    |
    | DELETE
    | /api/v1/service-categories/{serviceCategory}
    |
    | Menggunakan Soft Delete.
    |
    */

    public function destroy(
        ServiceCategory $serviceCategory
    ): JsonResponse {
        try {

            /*
            |--------------------------------------------------------------------------
            | SOFT DELETE
            |--------------------------------------------------------------------------
            */

            $serviceCategory->delete();

            /*
            |--------------------------------------------------------------------------
            | RESPONSE
            |--------------------------------------------------------------------------
            */

            return response()->json([
                'success' => true,

                'message' =>
                'Kategori layanan berhasil dipindahkan ke sampah.',

                'data'    => null,
            ]);

        } catch (Throwable $exception) {

            report($exception);

            return response()->json([
                'success' => false,

                'message' =>
                'Kategori layanan gagal dihapus.',
            ], 500);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | TRASHED
    |--------------------------------------------------------------------------
    |
    | GET
    | /api/v1/service-categories/trashed
    |
    */

    public function trashed(
        IndexServiceCategoryRequest $request
    ): JsonResponse {
        try {

            $filters = $request->validated();

            $search = $filters['search'] ?? null;

            $status = $filters['status'] ?? null;

            $sortBy = $filters['sort_by'] ?? 'deleted_at';

            $sortDirection = $filters['sort_direction'] ?? 'desc';

            $perPage = (int) (
                $filters['per_page'] ?? 15
            );

            /*
            |--------------------------------------------------------------------------
            | ONLY TRASHED
            |--------------------------------------------------------------------------
            */

            $query = ServiceCategory::onlyTrashed();

            /*
            |--------------------------------------------------------------------------
            | SEARCH
            |--------------------------------------------------------------------------
            */

            if (
                $search !== null
                && trim((string) $search) !== ''
            ) {

                $keyword = mb_strtolower(
                    trim((string) $search)
                );

                $pattern = '%' . $keyword . '%';

                $query->where(
                    function (Builder $builder) use ($pattern): void {

                        $builder
                            ->whereRaw(
                                'LOWER(code) LIKE ?',
                                [$pattern]
                            )
                            ->orWhereRaw(
                                'LOWER(name) LIKE ?',
                                [$pattern]
                            )
                            ->orWhereRaw(
                                "LOWER(COALESCE(description, '')) LIKE ?",
                                [$pattern]
                            );
                    }
                );
            }

            /*
            |--------------------------------------------------------------------------
            | STATUS
            |--------------------------------------------------------------------------
            */

            if ($status !== null) {

                $query->where(
                    'status',
                    $status
                );
            }

            /*
            |--------------------------------------------------------------------------
            | PAGINATION
            |--------------------------------------------------------------------------
            */

            $serviceCategories = $query
                ->orderBy(
                    $sortBy,
                    $sortDirection
                )
                ->paginate($perPage)
                ->withQueryString();

            /*
            |--------------------------------------------------------------------------
            | RESOURCE
            |--------------------------------------------------------------------------
            */

            $data = collect(
                $serviceCategories->items()
            )
                ->map(
                    function (ServiceCategory $serviceCategory) use ($request): array {

                        return (
                            new ServiceCategoryResource(
                                $serviceCategory
                            )
                        )->resolve($request);
                    }
                )
                ->values()
                ->all();

            /*
            |--------------------------------------------------------------------------
            | RESPONSE
            |--------------------------------------------------------------------------
            */

            return response()->json([
                'success' => true,

                'message' =>
                'Data sampah kategori layanan berhasil diambil.',

                'data'    => $data,

                'meta'    => [
                    'current_page' =>
                    $serviceCategories->currentPage(),

                    'from'         =>
                    $serviceCategories->firstItem(),

                    'last_page'    =>
                    $serviceCategories->lastPage(),

                    'per_page'     =>
                    $serviceCategories->perPage(),

                    'to'           =>
                    $serviceCategories->lastItem(),

                    'total'        =>
                    $serviceCategories->total(),
                ],

                'links'   => [
                    'first' =>
                    $serviceCategories->url(1),

                    'last'  =>
                    $serviceCategories->url(
                        $serviceCategories->lastPage()
                    ),

                    'prev'  =>
                    $serviceCategories->previousPageUrl(),

                    'next'  =>
                    $serviceCategories->nextPageUrl(),
                ],
            ]);

        } catch (Throwable $exception) {

            report($exception);

            return response()->json([
                'success' => false,

                'message' =>
                'Data sampah kategori layanan gagal diambil.',
            ], 500);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | RESTORE
    |--------------------------------------------------------------------------
    |
    | PATCH
    | /api/v1/service-categories/{id}/restore
    |
    */

    public function restore(
        Request $request,
        int | string $id
    ): JsonResponse {
        try {

            /*
            |--------------------------------------------------------------------------
            | FIND TRASHED DATA
            |--------------------------------------------------------------------------
            */

            $serviceCategory =
            ServiceCategory::onlyTrashed()
                ->findOrFail($id);

            /*
            |--------------------------------------------------------------------------
            | RESTORE
            |--------------------------------------------------------------------------
            */

            $serviceCategory->restore();

            /*
            |--------------------------------------------------------------------------
            | REFRESH
            |--------------------------------------------------------------------------
            */

            $serviceCategory->refresh();

            /*
            |--------------------------------------------------------------------------
            | RESPONSE
            |--------------------------------------------------------------------------
            */

            return response()->json([
                'success' => true,

                'message' =>
                'Kategori layanan berhasil dipulihkan.',

                'data'    => (
                    new ServiceCategoryResource(
                        $serviceCategory
                    )
                )->resolve($request),
            ]);

        } catch (ModelNotFoundException) {

            return response()->json([
                'success' => false,

                'message' =>
                'Kategori layanan yang dihapus tidak ditemukan.',
            ], 404);

        } catch (Throwable $exception) {

            report($exception);

            return response()->json([
                'success' => false,

                'message' =>
                'Kategori layanan gagal dipulihkan.',
            ], 500);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | FORCE DELETE
    |--------------------------------------------------------------------------
    |
    | DELETE
    | /api/v1/service-categories/{id}/force-delete
    |
    */

    public function forceDelete(
        int | string $id
    ): JsonResponse {
        try {

            /*
            |--------------------------------------------------------------------------
            | FIND TRASHED DATA
            |--------------------------------------------------------------------------
            */

            $serviceCategory =
            ServiceCategory::onlyTrashed()
                ->findOrFail($id);

            /*
            |--------------------------------------------------------------------------
            | FORCE DELETE
            |--------------------------------------------------------------------------
            */

            $serviceCategory->forceDelete();

            /*
            |--------------------------------------------------------------------------
            | RESPONSE
            |--------------------------------------------------------------------------
            */

            return response()->json([
                'success' => true,

                'message' =>
                'Kategori layanan berhasil dihapus permanen.',

                'data'    => null,
            ]);

        } catch (ModelNotFoundException) {

            return response()->json([
                'success' => false,

                'message' =>
                'Kategori layanan yang dihapus tidak ditemukan.',
            ], 404);

        } catch (Throwable $exception) {

            report($exception);

            return response()->json([
                'success' => false,

                'message' =>
                'Kategori layanan gagal dihapus permanen.',
            ], 500);
        }
    }
}

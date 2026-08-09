<?php

declare (strict_types = 1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    private const SORTABLE_COLUMNS = [
        'id', 'service_code', 'name', 'base_price',
        'estimated_duration_minutes', 'unit', 'status', 'created_at',
    ];

    public function index(Request $request): JsonResponse
    {
        $validator = Validator::make($request->query(), [
            'search'              => ['nullable', 'string', 'max:100'],
            'status'              => ['nullable', 'in:active,inactive'],
            'service_category_id' => ['nullable', 'integer', 'exists:service_categories,id'],
            'sort_by'             => ['nullable', 'in:' . implode(',', self::SORTABLE_COLUMNS)],
            'sort_direction'      => ['nullable', 'in:asc,desc'],
            'per_page'            => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false, 'message' => 'Parameter filter tidak valid.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $filters  = $validator->validated();
        $services = Service::query()->with('category')
            ->search($filters['search'] ?? null)
            ->status($filters['status'] ?? null)
            ->when(isset($filters['service_category_id']), fn(Builder $query) =>
                $query->where('service_category_id', $filters['service_category_id']))
            ->orderBy($filters['sort_by'] ?? 'created_at', $filters['sort_direction'] ?? 'desc')
            ->paginate((int) ($filters['per_page'] ?? 15))->withQueryString();

        return response()->json([
            'success' => true, 'message' => 'Data service berhasil diambil.',
            'data'    => ServiceResource::collection($services->items())->resolve($request),
            'meta'    => [
                'current_page' => $services->currentPage(), 'from'  => $services->firstItem(),
                'last_page'    => $services->lastPage(), 'per_page' => $services->perPage(),
                'to'           => $services->lastItem(), 'total'    => $services->total(),
            ],
            'links'   => [
                'first' => $services->url(1), 'last'            => $services->url($services->lastPage()),
                'prev'  => $services->previousPageUrl(), 'next' => $services->nextPageUrl(),
            ],
        ]);
    }

    public function store(StoreServiceRequest $request): JsonResponse
    {
        $service = Service::create($request->validated())->load('category');
        return response()->json([
            'success' => true, 'message' => 'Service berhasil dibuat.',
            'data'    => (new ServiceResource($service))->resolve($request),
        ], 201);
    }

    public function show(Request $request, Service $service): JsonResponse
    {
        return response()->json([
            'success' => true, 'message' => 'Detail service berhasil diambil.',
            'data'    => (new ServiceResource($service->load('category')))->resolve($request),
        ]);
    }

    public function update(Request $request, Service $service): JsonResponse
    {
        $rules = array_map(
            fn(array $rule): array=> array_merge(['sometimes'], $rule),
            $this->rules($service->id)
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->validationError($validator);
        }

        $service->update($validator->validated());
        return response()->json([
            'success' => true, 'message' => 'Service berhasil diperbarui.',
            'data'    => (new ServiceResource($service->fresh()->load('category')))->resolve($request),
        ]);
    }

    public function destroy(Service $service): JsonResponse
    {
        $service->delete();
        return response()->json(['success' => true, 'message' => 'Service berhasil dihapus.']);
    }

    public function trashed(): JsonResponse
    {
        return response()->json([
            'success' => true, 'message' => 'Data service terhapus berhasil diambil.',
            'data'    => Service::onlyTrashed()->with('category')->latest()->get(),
        ]);
    }

    public function restore(int $id): JsonResponse
    {
        $service = Service::onlyTrashed()->find($id);
        if (! $service) {
            return $this->notFound();
        }

        $service->restore();
        return response()->json([
            'success' => true, 'message' => 'Service berhasil dipulihkan.',
            'data'    => $service->fresh()->load('category'),
        ]);
    }

    public function forceDelete(int $id): JsonResponse
    {
        $service = Service::onlyTrashed()->find($id);
        if (! $service) {
            return $this->notFound();
        }

        $service->forceDelete();
        return response()->json(['success' => true, 'message' => 'Service berhasil dihapus permanen.']);
    }

    public function toggleStatus(Service $service): JsonResponse
    {
        $service->update([
            'status' => $service->status === Service::STATUS_ACTIVE ? 'inactive' : Service::STATUS_ACTIVE,
        ]);
        return response()->json([
            'success' => true, 'message' => 'Status service berhasil diperbarui.',
            'data'    => $service->fresh()->load('category'),
        ]);
    }

    private function rules(?int $ignoreId = null): array
    {
        $uniqueCode = 'unique:services,service_code' . ($ignoreId ? ',' . $ignoreId : '');
        return [
            'service_category_id'        => ['required', 'integer', 'exists:service_categories,id'],
            'service_code'               => ['nullable', 'string', 'max:50', $uniqueCode],
            'name'                       => ['required', 'string', 'max:150'],
            'description'                => ['nullable', 'string'], 'base_price'       => ['nullable', 'numeric', 'min:0'],
            'estimated_duration_minutes' => ['nullable', 'integer', 'min:0'],
            'unit'                       => ['nullable', 'string', 'max:50'], 'status' => ['nullable', 'in:active,inactive'],
        ];
    }

    private function validationError($validator): JsonResponse
    {
        return response()->json([
            'success' => false, 'message' => 'Validasi gagal.', 'errors' => $validator->errors(),
        ], 422);
    }

    private function notFound(): JsonResponse
    {
        return response()->json(['success' => false, 'message' => 'Service tidak ditemukan.'], 404);
    }
}

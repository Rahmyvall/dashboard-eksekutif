<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePerformanceIndicatorRequest;
use App\Http\Requests\UpdatePerformanceIndicatorRequest;
use App\Models\PerformanceIndicator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class PerformanceIndicatorController extends Controller
{
    /**
     * GET /api/performance-indicators
     */
    public function index(Request $request): JsonResponse
    {
        $query = PerformanceIndicator::query();

        // Pencarian berdasarkan code, name, description, atau unit
        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));

            $query->where(function ($subQuery) use ($search) {
                $subQuery
                    ->where('code', 'ilike', "%{$search}%")
                    ->orWhere('name', 'ilike', "%{$search}%")
                    ->orWhere('description', 'ilike', "%{$search}%")
                    ->orWhere('unit', 'ilike', "%{$search}%");
            });
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter arah target
        if ($request->filled('target_direction')) {
            $query->where(
                'target_direction',
                $request->input('target_direction')
            );
        }

        $perPage = (int) $request->input('per_page', 10);

        // Batasi agar request tidak mengambil data terlalu banyak
        $perPage = max(1, min($perPage, 100));

        $indicators = $query
            ->orderByDesc('created_at')
            ->paginate($perPage);

        return response()->json([
            'success'    => true,
            'message'    => 'Data performance indicator berhasil diambil.',
            'data'       => $indicators->items(),
            'pagination' => [
                'current_page' => $indicators->currentPage(),
                'last_page'    => $indicators->lastPage(),
                'per_page'     => $indicators->perPage(),
                'total'        => $indicators->total(),
                'from'         => $indicators->firstItem(),
                'to'           => $indicators->lastItem(),
            ],
        ]);
    }

    /**
     * POST /api/performance-indicators
     */
    public function store(
        StorePerformanceIndicatorRequest $request
    ): JsonResponse {
        try {
            $indicator = PerformanceIndicator::create(
                $request->validated()
            );

            return response()->json([
                'success' => true,
                'message' => 'Performance indicator berhasil ditambahkan.',
                'data'    => $indicator,
            ], 201);
        } catch (Throwable $error) {
            report($error);

            return response()->json([
                'success' => false,
                'message' => 'Performance indicator gagal ditambahkan.',
                'error'   => config('app.debug')
                    ? $error->getMessage()
                    : null,
            ], 500);
        }
    }

    /**
     * GET /api/performance-indicators/{indicator}
     */
    public function show(
        PerformanceIndicator $indicator
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => 'Detail performance indicator berhasil diambil.',
            'data'    => $indicator,
        ]);
    }

    /**
     * PUT/PATCH /api/performance-indicators/{indicator}
     */
    public function update(
        UpdatePerformanceIndicatorRequest $request,
        PerformanceIndicator $indicator
    ): JsonResponse {
        try {
            $indicator->update($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Performance indicator berhasil diperbarui.',
                'data'    => $indicator->fresh(),
            ]);
        } catch (Throwable $error) {
            report($error);

            return response()->json([
                'success' => false,
                'message' => 'Performance indicator gagal diperbarui.',
                'error'   => config('app.debug')
                    ? $error->getMessage()
                    : null,
            ], 500);
        }
    }

    /**
     * DELETE /api/performance-indicators/{indicator}
     */
    public function destroy(
        PerformanceIndicator $indicator
    ): JsonResponse {
        try {
            $deletedData = [
                'id'   => $indicator->id,
                'code' => $indicator->code,
                'name' => $indicator->name,
            ];

            $indicator->delete();

            return response()->json([
                'success' => true,
                'message' => 'Performance indicator berhasil dihapus.',
                'data'    => $deletedData,
            ]);
        } catch (Throwable $error) {
            report($error);

            return response()->json([
                'success' => false,
                'message' => 'Performance indicator gagal dihapus.',
                'error'   => config('app.debug')
                    ? $error->getMessage()
                    : null,
            ], 500);
        }
    }
}

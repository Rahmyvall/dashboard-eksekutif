<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StorePositionRequest;
use App\Http\Requests\Api\UpdatePositionRequest;
use App\Http\Resources\PositionResource;
use App\Models\Department;
use App\Models\Position;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Throwable;

class PositionController extends Controller
{
    public function index(Request $request)
    {
        $search       = trim((string) $request->query('search', ''));
        $status       = trim((string) $request->query('status', ''));
        $departmentId = $request->query('department_id');
        $level        = $request->query('level');
        $perPage      = max(1, min((int) $request->query('per_page', 10), 100));

        $operator = DB::connection()->getDriverName() === 'pgsql'
            ? 'ilike'
            : 'like';

        $positions = Position::query()
            ->with('department')
            ->when($search !== '', function ($query) use ($search, $operator): void {
                $query->where(function ($subQuery) use ($search, $operator): void {
                    $subQuery
                        ->where('code', $operator, "%{$search}%")
                        ->orWhere('name', $operator, "%{$search}%")
                        ->orWhere('description', $operator, "%{$search}%")
                        ->orWhereHas('department', function ($departmentQuery) use ($search, $operator): void {
                            $departmentQuery
                                ->where('code', $operator, "%{$search}%")
                                ->orWhere('name', $operator, "%{$search}%");
                        });
                });
            })
            ->when(
                in_array($status, ['active', 'inactive'], true),
                fn($query) => $query->where('status', $status)
            )
            ->when(
                filled($departmentId),
                fn($query) => $query->where('department_id', (int) $departmentId)
            )
            ->when(
                filled($level),
                fn($query) => $query->where('level', (int) $level)
            )
            ->orderByDesc('level')
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();

        return PositionResource::collection($positions)->additional([
            'success' => true,
            'message' => 'Daftar jabatan berhasil diambil.',
        ]);
    }

    public function store(StorePositionRequest $request): JsonResponse
    {
        try {
            $position = DB::transaction(
                fn(): Position => Position::create($request->validated())
            );

            $position->load('department');

            return response()->json([
                'success' => true,
                'message' => 'Jabatan berhasil ditambahkan.',
                'data'    => new PositionResource($position),
            ], 201);
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'success' => false,
                'message' => 'Jabatan gagal ditambahkan.',
            ], 500);
        }
    }

    public function show(Position $position)
    {
        $position->load('department');

        return (new PositionResource($position))->additional([
            'success' => true,
            'message' => 'Detail jabatan berhasil diambil.',
        ]);
    }

    public function update(
        UpdatePositionRequest $request,
        Position $position
    ): JsonResponse {
        try {
            DB::transaction(function () use ($request, $position): void {
                $position->update($request->validated());
            });

            $position->refresh()->load('department');

            return response()->json([
                'success' => true,
                'message' => 'Jabatan berhasil diperbarui.',
                'data'    => new PositionResource($position),
            ]);
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'success' => false,
                'message' => 'Jabatan gagal diperbarui.',
            ], 500);
        }
    }

    public function destroy(Position $position): JsonResponse
    {
        try {
            DB::transaction(fn() => $position->delete());

            return response()->json([
                'success' => true,
                'message' => 'Jabatan berhasil dipindahkan ke sampah.',
            ]);
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'success' => false,
                'message' => 'Jabatan gagal dihapus.',
            ], 500);
        }
    }

    public function trash(Request $request)
    {
        $search       = trim((string) $request->query('search', ''));
        $departmentId = $request->query('department_id');
        $perPage      = max(1, min((int) $request->query('per_page', 10), 100));

        $operator = DB::connection()->getDriverName() === 'pgsql'
            ? 'ilike'
            : 'like';

        $positions = Position::onlyTrashed()
            ->with('department')
            ->when($search !== '', function ($query) use ($search, $operator): void {
                $query->where(function ($subQuery) use ($search, $operator): void {
                    $subQuery
                        ->where('code', $operator, "%{$search}%")
                        ->orWhere('name', $operator, "%{$search}%")
                        ->orWhere('description', $operator, "%{$search}%")
                        ->orWhereHas('department', function ($departmentQuery) use ($search, $operator): void {
                            $departmentQuery
                                ->where('code', $operator, "%{$search}%")
                                ->orWhere('name', $operator, "%{$search}%");
                        });
                });
            })
            ->when(
                filled($departmentId),
                fn($query) => $query->where('department_id', (int) $departmentId)
            )
            ->orderByDesc('deleted_at')
            ->paginate($perPage)
            ->withQueryString();

        return PositionResource::collection($positions)->additional([
            'success' => true,
            'message' => 'Daftar sampah jabatan berhasil diambil.',
        ]);
    }

    public function restore(int $id): JsonResponse
    {
        try {
            $position = DB::transaction(function () use ($id): Position {
                $position = Position::onlyTrashed()->findOrFail($id);

                $departmentExists = Department::query()
                    ->where('id', $position->department_id)
                    ->exists();

                if (! $departmentExists) {
                    throw new RuntimeException(
                        'Departemen jabatan sudah tidak tersedia.'
                    );
                }

                $position->restore();

                return $position;
            });

            $position->load('department');

            return response()->json([
                'success' => true,
                'message' => 'Jabatan berhasil dikembalikan.',
                'data'    => new PositionResource($position),
            ]);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Data jabatan terhapus tidak ditemukan.',
            ], 404);
        } catch (RuntimeException $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], 422);
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'success' => false,
                'message' => 'Jabatan gagal dikembalikan.',
            ], 500);
        }
    }

    public function forceDelete(int $id): JsonResponse
    {
        try {
            DB::transaction(function () use ($id): void {
                Position::onlyTrashed()->findOrFail($id)->forceDelete();
            });

            return response()->json([
                'success' => true,
                'message' => 'Jabatan berhasil dihapus secara permanen.',
            ]);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Data jabatan terhapus tidak ditemukan.',
            ], 404);
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'success' => false,
                'message' => 'Jabatan gagal dihapus permanen. Data mungkin masih digunakan.',
            ], 500);
        }
    }
}

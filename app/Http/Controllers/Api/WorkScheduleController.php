<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\WorkSchedule\StoreWorkScheduleRequest;
use App\Http\Requests\WorkSchedule\UpdateWorkScheduleRequest;
use App\Http\Resources\WorkScheduleResource;
use App\Models\WorkSchedule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class WorkScheduleController extends Controller
{
    /**
     * Menampilkan daftar jadwal kerja.
     *
     * Mendukung parameter:
     * - search
     * - status
     * - sort_by
     * - sort_direction
     * - per_page
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $validated = $request->validate([
            'search'         => [
                'nullable',
                'string',
                'max:100',
            ],
            'status'         => [
                'nullable',
                'in:active,inactive',
            ],
            'sort_by'        => [
                'nullable',
                'in:id,name,start_time,end_time,working_hours,status,created_at,updated_at',
            ],
            'sort_direction' => [
                'nullable',
                'in:asc,desc',
            ],
            'per_page'       => [
                'nullable',
                'integer',
                'min:1',
                'max:100',
            ],
        ]);

        $search        = $validated['search'] ?? null;
        $status        = $validated['status'] ?? null;
        $sortBy        = $validated['sort_by'] ?? 'id';
        $sortDirection = $validated['sort_direction'] ?? 'desc';
        $perPage       = $validated['per_page'] ?? 10;

        $workSchedules = WorkSchedule::query()
            ->when($search, function ($query, string $search): void {
                /*
                 * LOWER digunakan agar pencarian tetap tidak membedakan
                 * huruf besar dan kecil pada PostgreSQL.
                 */
                $query->whereRaw(
                    'LOWER(name) LIKE ?',
                    ['%' . mb_strtolower($search) . '%']
                );
            })
            ->when($status, function ($query, string $status): void {
                $query->where('status', $status);
            })
            ->orderBy($sortBy, $sortDirection)
            ->paginate($perPage)
            ->withQueryString();

        return WorkScheduleResource::collection($workSchedules)
            ->additional([
                'success' => true,
                'message' => 'Data jadwal kerja berhasil ditampilkan.',
            ]);
    }

    /**
     * Menambahkan jadwal kerja baru.
     */
    public function store(
        StoreWorkScheduleRequest $request
    ): JsonResponse {
        $workSchedule = WorkSchedule::query()->create(
            $request->validated()
        );

        return (new WorkScheduleResource($workSchedule))
            ->additional([
                'success' => true,
                'message' => 'Jadwal kerja berhasil ditambahkan.',
            ])
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Menampilkan detail jadwal kerja.
     */
    public function show(
        WorkSchedule $workSchedule
    ): WorkScheduleResource {
        return (new WorkScheduleResource($workSchedule))
            ->additional([
                'success' => true,
                'message' => 'Detail jadwal kerja berhasil ditampilkan.',
            ]);
    }

    /**
     * Memperbarui jadwal kerja.
     */
    public function update(
        UpdateWorkScheduleRequest $request,
        WorkSchedule $workSchedule
    ): WorkScheduleResource {
        $workSchedule->update($request->validated());

        $workSchedule->refresh();

        return (new WorkScheduleResource($workSchedule))
            ->additional([
                'success' => true,
                'message' => 'Jadwal kerja berhasil diperbarui.',
            ]);
    }

    /**
     * Menghapus jadwal kerja.
     */
    public function destroy(
        WorkSchedule $workSchedule
    ): JsonResponse {
        $workSchedule->delete();

        return response()->json([
            'success' => true,
            'message' => 'Jadwal kerja berhasil dihapus.',
            'data'    => null,
        ]);
    }
}

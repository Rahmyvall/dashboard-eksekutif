<?php

declare (strict_types = 1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StorePerformancePeriodApiRequest;
use App\Http\Requests\Api\UpdatePerformancePeriodApiRequest;
use App\Http\Resources\PerformancePeriodResource;
use App\Models\PerformancePeriod;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Throwable;

class PerformancePeriodController extends Controller
{
    /**
     * @var array<int, string>
     */
    private const PERIOD_TYPES = [
        'monthly',
        'quarterly',
        'semester',
        'annual',
    ];

    /**
     * @var array<int, string>
     */
    private const STATUSES = [
        'draft',
        'active',
        'completed',
        'inactive',
    ];

    public function index(
        Request $request
    ): AnonymousResourceCollection {
        $search = trim(
            (string) $request->query('search', '')
        );

        $status = strtolower(
            trim((string) $request->query('status', ''))
        );

        $periodType = strtolower(
            trim((string) $request->query('period_type', ''))
        );

        $date = trim(
            (string) $request->query('date', '')
        );

        $sort = trim(
            (string) $request->query(
                'sort',
                'start_date'
            )
        );

        $direction = strtolower(
            trim(
                (string) $request->query(
                    'direction',
                    'desc'
                )
            )
        );

        $allowedSorts = [
            'id',
            'name',
            'start_date',
            'end_date',
            'period_type',
            'status',
            'created_at',
            'updated_at',
        ];

        if (! in_array($sort, $allowedSorts, true)) {
            $sort = 'start_date';
        }

        if (! in_array($direction, ['asc', 'desc'], true)) {
            $direction = 'desc';
        }

        $perPage = (int) $request->query(
            'per_page',
            15
        );

        $perPage = min(
            max($perPage, 1),
            100
        );

        $validDate = $this->isValidDate($date)
            ? $date
            : null;

        $performancePeriods = PerformancePeriod::query()
            ->when(
                $search !== '',
                function (Builder $query) use ($search): void {
                    $keyword = '%' . mb_strtolower($search) . '%';

                    $query->where(
                        function (Builder $subQuery) use (
                            $keyword
                        ): void {
                            $subQuery
                                ->whereRaw(
                                    'LOWER(name) LIKE ?',
                                    [$keyword]
                                )
                                ->orWhereRaw(
                                    'LOWER(period_type) LIKE ?',
                                    [$keyword]
                                )
                                ->orWhereRaw(
                                    'LOWER(status) LIKE ?',
                                    [$keyword]
                                );
                        }
                    );
                }
            )
            ->when(
                in_array($status, self::STATUSES, true),
                fn(Builder $query): Builder => $query
                    ->where('status', $status)
            )
            ->when(
                in_array($periodType, self::PERIOD_TYPES, true),
                fn(Builder $query): Builder => $query
                    ->where(
                        'period_type',
                        $periodType
                    )
            )
            ->when(
                $validDate !== null,
                fn(Builder $query): Builder => $query
                    ->whereDate(
                        'start_date',
                        '<=',
                        $validDate
                    )
                    ->whereDate(
                        'end_date',
                        '>=',
                        $validDate
                    )
            )
            ->orderBy($sort, $direction)
            ->orderBy('id')
            ->paginate($perPage)
            ->withQueryString();

        return PerformancePeriodResource::collection(
            $performancePeriods
        )->additional([
            'message' =>
            'Daftar periode penilaian berhasil diambil.',

            'filters' => [
                'search'      => $search !== ''
                    ? $search
                    : null,

                'status'      => $status !== ''
                    ? $status
                    : null,

                'period_type' => $periodType !== ''
                    ? $periodType
                    : null,

                'date'        => $validDate,
                'sort'        => $sort,
                'direction'   => $direction,
                'per_page'    => $perPage,
            ],
        ]);
    }

    public function store(
        StorePerformancePeriodApiRequest $request
    ): JsonResponse {
        try {
            $performancePeriod = DB::transaction(
                function () use (
                    $request
                ): PerformancePeriod {
                    return PerformancePeriod::query()
                        ->create(
                            $request->validated()
                        );
                }
            );

            return (new PerformancePeriodResource(
                $performancePeriod->refresh()
            ))
                ->additional([
                    'message' =>
                    'Periode penilaian berhasil dibuat.',
                ])
                ->response()
                ->setStatusCode(
                    Response::HTTP_CREATED
                );
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'message' =>
                'Periode penilaian gagal dibuat.',

                'error'   => config('app.debug')
                    ? $exception->getMessage()
                    : null,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(
        PerformancePeriod $performancePeriod
    ): PerformancePeriodResource {
        return (new PerformancePeriodResource(
            $performancePeriod
        ))->additional([
            'message' =>
            'Detail periode penilaian berhasil diambil.',
        ]);
    }

    public function update(
        UpdatePerformancePeriodApiRequest $request,
        PerformancePeriod $performancePeriod
    ): JsonResponse {
        try {
            DB::transaction(
                function () use (
                    $request,
                    $performancePeriod
                ): void {
                    $performancePeriod->update(
                        $request->validated()
                    );
                }
            );

            return (new PerformancePeriodResource(
                $performancePeriod->refresh()
            ))
                ->additional([
                    'message' =>
                    'Periode penilaian berhasil diperbarui.',
                ])
                ->response()
                ->setStatusCode(
                    Response::HTTP_OK
                );
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'message' =>
                'Periode penilaian gagal diperbarui.',

                'error'   => config('app.debug')
                    ? $exception->getMessage()
                    : null,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(
        PerformancePeriod $performancePeriod
    ): Response | JsonResponse {
        try {
            DB::transaction(
                function () use (
                    $performancePeriod
                ): void {
                    $performancePeriod->delete();
                }
            );

            return response()->noContent();
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'message' =>
                'Periode penilaian gagal dihapus.',

                'error'   => config('app.debug')
                    ? $exception->getMessage()
                    : null,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function current():
    PerformancePeriodResource | JsonResponse {
        $today = now()->toDateString();

        $performancePeriod = PerformancePeriod::query()
            ->where('status', 'active')
            ->whereDate(
                'start_date',
                '<=',
                $today
            )
            ->whereDate(
                'end_date',
                '>=',
                $today
            )
            ->orderBy('end_date')
            ->orderBy('start_date')
            ->first();

        if (! $performancePeriod) {
            return response()->json([
                'message' =>
                'Tidak ada periode penilaian aktif saat ini.',
                'data'    => null,
            ], Response::HTTP_NOT_FOUND);
        }

        return (new PerformancePeriodResource(
            $performancePeriod
        ))->additional([
            'message' =>
            'Periode penilaian aktif berhasil diambil.',
        ]);
    }

    public function summary(): JsonResponse
    {
        $today = now()->toDateString();

        $query = PerformancePeriod::query();

        return response()->json([
            'message' =>
            'Ringkasan periode penilaian berhasil diambil.',

            'data'    => [
                'total'     => (clone $query)->count(),

                'draft'     => (clone $query)
                    ->where('status', 'draft')
                    ->count(),

                'active'    => (clone $query)
                    ->where('status', 'active')
                    ->count(),

                'completed' => (clone $query)
                    ->where('status', 'completed')
                    ->count(),

                'inactive'  => (clone $query)
                    ->where('status', 'inactive')
                    ->count(),

                'current'   => (clone $query)
                    ->where('status', 'active')
                    ->whereDate(
                        'start_date',
                        '<=',
                        $today
                    )
                    ->whereDate(
                        'end_date',
                        '>=',
                        $today
                    )
                    ->count(),

                'upcoming'  => (clone $query)
                    ->whereDate(
                        'start_date',
                        '>',
                        $today
                    )
                    ->count(),

                'expired'   => (clone $query)
                    ->whereDate(
                        'end_date',
                        '<',
                        $today
                    )
                    ->count(),
            ],
        ]);
    }

    private function isValidDate(
        string $date
    ): bool {
        if ($date === '') {
            return false;
        }

        $parsedDate = DateTimeImmutable::createFromFormat(
            '!Y-m-d',
            $date
        );

        return $parsedDate !== false
        && $parsedDate->format('Y-m-d') === $date;
    }
}

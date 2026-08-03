<?php

declare (strict_types = 1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePerformancePeriodRequest;
use App\Http\Requests\UpdatePerformancePeriodRequest;
use App\Models\PerformancePeriod;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
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

    public function index(Request $request): View
    {
        $search     = trim((string) $request->query('search', ''));
        $status     = trim((string) $request->query('status', ''));
        $periodType = trim((string) $request->query('period_type', ''));
        $date       = trim((string) $request->query('date', ''));

        $validDate = $this->isValidDate($date) ? $date : null;

        $performancePeriods = PerformancePeriod::query()
            ->when(
                $search !== '',
                function (Builder $query) use ($search): void {
                    $keyword = '%' . mb_strtolower($search) . '%';

                    $query->where(
                        function (Builder $subQuery) use ($keyword): void {
                            $subQuery
                                ->whereRaw('LOWER(name) LIKE ?', [$keyword])
                                ->orWhereRaw('LOWER(period_type) LIKE ?', [$keyword])
                                ->orWhereRaw('LOWER(status) LIKE ?', [$keyword]);
                        }
                    );
                }
            )
            ->when(
                in_array($status, self::STATUSES, true),
                fn(Builder $query): Builder => $query->where('status', $status)
            )
            ->when(
                in_array($periodType, self::PERIOD_TYPES, true),
                fn(Builder $query): Builder => $query->where(
                    'period_type',
                    $periodType
                )
            )
            ->when(
                $validDate !== null,
                fn(Builder $query): Builder => $query
                    ->whereDate('start_date', '<=', $validDate)
                    ->whereDate('end_date', '>=', $validDate)
            )
            ->orderByDesc('start_date')
            ->orderByDesc('end_date')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('super-admin.performance-periods.index', [
            'performancePeriods' => $performancePeriods,
            'periodTypes'        => collect(self::PERIOD_TYPES),
            'statuses'           => collect(self::STATUSES),
            'search'             => $search,
            'status'             => $status,
            'periodType'         => $periodType,
            'date'               => $date,
        ]);
    }

    public function create(): View
    {
        return view('super-admin.performance-periods.create', [
            'periodTypes' => collect(self::PERIOD_TYPES),
            'statuses'    => collect(self::STATUSES),
        ]);
    }

    public function store(
        StorePerformancePeriodRequest $request
    ): RedirectResponse {
        $validated = $request->validated();

        try {
            $performancePeriod = DB::transaction(
                fn(): PerformancePeriod => PerformancePeriod::query()->create(
                    $this->normalizedPayload($validated)
                )
            );

            return redirect()
                ->route(
                    'super-admin.performance-periods.show',
                    $performancePeriod
                )
                ->with(
                    'success',
                    'Periode penilaian berhasil ditambahkan.'
                );
        } catch (Throwable $exception) {
            report($exception);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Periode penilaian gagal ditambahkan. Silakan coba kembali.'
                );
        }
    }

    public function show(
        PerformancePeriod $performancePeriod
    ): View {
        return view('super-admin.performance-periods.show', [
            'performancePeriod' => $performancePeriod,
        ]);
    }

    public function edit(
        PerformancePeriod $performancePeriod
    ): View {
        return view('super-admin.performance-periods.edit', [
            'performancePeriod' => $performancePeriod,
            'periodTypes'       => collect(self::PERIOD_TYPES),
            'statuses'          => collect(self::STATUSES),
        ]);
    }

    public function update(
        UpdatePerformancePeriodRequest $request,
        PerformancePeriod $performancePeriod
    ): RedirectResponse {
        $validated = $request->validated();

        try {
            DB::transaction(
                function () use (
                    $performancePeriod,
                    $validated
                ): void {
                    $performancePeriod->update(
                        $this->normalizedPayload($validated)
                    );
                }
            );

            $performancePeriod->refresh();

            return redirect()
                ->route(
                    'super-admin.performance-periods.show',
                    $performancePeriod
                )
                ->with(
                    'success',
                    'Periode penilaian berhasil diperbarui.'
                );
        } catch (Throwable $exception) {
            report($exception);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Periode penilaian gagal diperbarui. Silakan coba kembali.'
                );
        }
    }

    public function destroy(
        PerformancePeriod $performancePeriod
    ): RedirectResponse {
        try {
            DB::transaction(
                function () use ($performancePeriod): void {
                    $performancePeriod->delete();
                }
            );

            return redirect()
                ->route('super-admin.performance-periods.index')
                ->with(
                    'success',
                    'Periode penilaian berhasil dihapus.'
                );
        } catch (Throwable $exception) {
            report($exception);

            return back()->with(
                'error',
                'Periode penilaian gagal dihapus. Data kemungkinan masih digunakan oleh data lain.'
            );
        }
    }

    /**
     * @param array<string, mixed> $validated
     * @return array<string, string>
     */
    private function normalizedPayload(array $validated): array
    {
        return [
            'name'        => trim((string) $validated['name']),
            'start_date'  => (string) $validated['start_date'],
            'end_date'    => (string) $validated['end_date'],
            'period_type' => strtolower(
                trim((string) $validated['period_type'])
            ),
            'status'      => strtolower(
                trim((string) $validated['status'])
            ),
        ];
    }

    private function isValidDate(string $date): bool
    {
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

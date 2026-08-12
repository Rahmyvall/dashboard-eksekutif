<?php

declare (strict_types = 1);

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Throwable;

class DashboardController extends Controller
{
    public function index(): ViewContract
    {
        $currentUser = Auth::user();


        $activeRoleName  = $this->resolveActiveRoleName($currentUser);
        $activeRoleLabel = Str::of($activeRoleName)
            ->replace('_', ' ')
            ->upper()
            ->toString();

        $dashboardView = $this->resolveDashboardView();

        if ($dashboardView === 'dashboards.admin-operasional') {
            return view($dashboardView, [
                'activeRoleName' => $activeRoleName,
                'activeRoleLabel' => $activeRoleLabel,
                ...$this->buildAdminOperasionalDashboardPayload($currentUser),
            ]);
        }

        $positions             = $this->getPositions();
        $performancePeriods    = $this->getPerformancePeriods();
        $performanceIndicators = $this->getPerformanceIndicators();
        $serviceCategories     = $this->getServiceCategories();
$services = $this->getServices();


        $branchSummary            = $this->buildBranchSummary();
        $performancePeriodSummary = $this->buildPerformancePeriodSummary();
        $currentPerformancePeriod = $this->getCurrentPerformancePeriod();

        $indicatorSummary          = $this->buildIndicatorSummary();
        $indicatorWeightChart      = $this->buildIndicatorWeightChart();
        $indicatorDirectionSummary = $this->buildIndicatorDirectionSummary();

        $serviceCategorySummary = $this->buildServiceCategorySummary();
$serviceSummary = $this->buildServiceSummary();
        $invoicePaymentLineChart = $this->buildInvoicePaymentLineChart();

        $departmentSummary      = $this->buildDepartmentSummary();
        $roleSummary            = $this->buildRoleSummary();

        $totalActivePositions = $this->countByStatus('positions', 'active');

        $usersUrl                 = $this->routeUrl('super-admin.users.index');
        $positionsUrl             = $this->routeUrl('super-admin.positions.index');
        $performancePeriodsUrl    = $this->routeUrl('super-admin.performance-periods.index');
        $performanceIndicatorsUrl = $this->routeUrl('super-admin.performance-indicators.index');
        $serviceCategoriesUrl     = $this->routeUrl('super-admin.service-categories.index');
$servicesUrl = $this->routeUrl('super-admin.services.index');


        $monitoringPriorities = $this->buildMonitoringPriorities(
            branchSummary: $branchSummary,
            performancePeriodSummary: $performancePeriodSummary,
            currentPerformancePeriod: $currentPerformancePeriod,
            indicatorSummary: $indicatorSummary,
            serviceCategorySummary: $serviceCategorySummary,
            positionsUrl: $positionsUrl,
            performancePeriodsUrl: $performancePeriodsUrl,
            performanceIndicatorsUrl: $performanceIndicatorsUrl,
            serviceCategoriesUrl: $serviceCategoriesUrl,
        );

        $systemActivities = $this->buildSystemActivities(
            branchSummary: $branchSummary,
            performancePeriodSummary: $performancePeriodSummary,
            indicatorSummary: $indicatorSummary,
            serviceCategorySummary: $serviceCategorySummary,
        );

        $branchAngle = min(
            360,
            max(0, (float) ($branchSummary['active_percentage'] ?? 0) * 3.6)
        );

        $indicatorAngle = min(
            360,
            max(0, (float) ($indicatorSummary['active_percentage'] ?? 0) * 3.6)
        );

        return view($dashboardView, [
            'activeRoleName'            => $activeRoleName,
            'activeRoleLabel'           => $activeRoleLabel,

            'usersUrl'                  => $usersUrl,
            'positionsUrl'              => $positionsUrl,
            'performancePeriodsUrl'     => $performancePeriodsUrl,
            'performanceIndicatorsUrl'  => $performanceIndicatorsUrl,
            'serviceCategoriesUrl'      => $serviceCategoriesUrl,
'servicesUrl' => $servicesUrl,


            'positions'                 => $positions,
            'performancePeriods'        => $performancePeriods,
            'performanceIndicators'     => $performanceIndicators,
            'serviceCategories'         => $serviceCategories,
'services' => $services,


            'branchSummary'             => $branchSummary,
            'branchAngle'               => $branchAngle,

            'performancePeriodSummary'  => $performancePeriodSummary,
            'currentPerformancePeriod'  => $currentPerformancePeriod,

            'indicatorSummary'          => $indicatorSummary,
            'indicatorWeightChart'      => $indicatorWeightChart,
            'indicatorDirectionSummary' => $indicatorDirectionSummary,
            'indicatorAngle'            => $indicatorAngle,

            'serviceCategorySummary'    => $serviceCategorySummary,
'serviceSummary' => $serviceSummary,
            'invoicePaymentLineChart'   => $invoicePaymentLineChart,

            'departmentSummary'         => $departmentSummary,
            'roleSummary'               => $roleSummary,
            'monitoringPriorities'      => $monitoringPriorities,
            'systemActivities'          => $systemActivities,

            'totalActivePositions'      => $totalActivePositions,
        ]);
    }

    private function getPositions(): Collection
    {
        return $this->getTableRows(
            table: 'positions',
            preferredOrderColumns: ['updated_at', 'created_at', 'id'],
            limit: 50,
        );
    }

    private function buildAdminOperasionalDashboardPayload(mixed $currentUser): array
    {
        $activeJobs = $this->countByStatus('employee_activities', 'submitted');
        $completedToday = $this->countTodayByStatus('employee_activities', 'activity_date', 'verified');
        $delayedJobs = $this->countByStatus('employee_activities', 'rejected');

        $totalBase = max(1, $activeJobs + $completedToday + $delayedJobs);
        $utilizationPercent = max(0, min(100, (int) round((($activeJobs + $completedToday) / $totalBase) * 100)));

        return [
            'statistics' => [
                [
                    'label' => 'Pekerjaan Aktif',
                    'value' => $activeJobs,
                    'suffix' => '',
                    'icon' => 'activity',
                    'description' => 'Monitoring pekerjaan operasional saat ini',
                    'trend' => '+0%',
                    'trend_type' => 'up',
                    'theme' => 'orange',
                ],
                [
                    'label' => 'Selesai Hari Ini',
                    'value' => $completedToday,
                    'suffix' => '',
                    'icon' => 'check-circle',
                    'description' => 'Pekerjaan yang tervalidasi hari ini',
                    'trend' => '+0%',
                    'trend_type' => 'up',
                    'theme' => 'green',
                ],
                [
                    'label' => 'Pekerjaan Tertunda',
                    'value' => $delayedJobs,
                    'suffix' => '',
                    'icon' => 'clock',
                    'description' => 'Perlu tindak lanjut prioritas',
                    'trend' => '+0',
                    'trend_type' => 'down',
                    'theme' => 'red',
                ],
                [
                    'label' => 'Utilisasi Sumber Daya',
                    'value' => $utilizationPercent,
                    'suffix' => '%',
                    'icon' => 'cpu',
                    'description' => 'Estimasi berbasis aktivitas operasional',
                    'trend' => '+0%',
                    'trend_type' => 'up',
                    'theme' => 'blue',
                ],
            ],
            'weeklyPerformance' => $this->buildAdminOperasionalWeeklyPerformance(),
            'operationalPriorities' => $this->buildAdminOperasionalPriorities($activeJobs, $completedToday, $delayedJobs),
            'operationalSchedules' => $this->buildAdminOperasionalSchedules(),
            'teamWorkloads' => $this->buildAdminOperasionalTeamWorkloads(),
            'operationalActivities' => $this->buildAdminOperasionalActivities(),
            'currentUserName' => (string) (data_get($currentUser, 'name') ?? 'Admin Operasional'),
        ];
    }

    private function buildAdminOperasionalWeeklyPerformance(): array
    {
        $days = collect(range(6, 0))->map(static function (int $offset): array {
            $date = now()->subDays($offset);

            return [
                'date' => $date->toDateString(),
                'day' => $date->translatedFormat('D'),
                'full_day' => $date->translatedFormat('l'),
                'scheduled' => 0,
                'completed' => 0,
            ];
        })->values();

        if (! Schema::hasTable('employee_activities') || ! Schema::hasColumn('employee_activities', 'activity_date')) {
            return $days->map(static function (array $item): array {
                unset($item['date']);

                return $item;
            })->all();
        }

        $startDate = now()->subDays(6)->toDateString();

        $rows = DB::table('employee_activities')
            ->select('activity_date')
            ->selectRaw('COUNT(*) as total')
            ->selectRaw("SUM(CASE WHEN status = 'verified' THEN 1 ELSE 0 END) as completed")
            ->whereDate('activity_date', '>=', $startDate)
            ->groupBy('activity_date')
            ->get();

        $mapped = $rows->keyBy(static fn(object $row): string => (string) data_get($row, 'activity_date'));

        return $days->map(static function (array $item) use ($mapped): array {
            $row = $mapped->get($item['date']);
            $item['scheduled'] = (int) (data_get($row, 'total') ?? 0);
            $item['completed'] = (int) (data_get($row, 'completed') ?? 0);
            unset($item['date']);

            return $item;
        })->all();
    }

    private function buildAdminOperasionalPriorities(int $activeJobs, int $completedToday, int $delayedJobs): array
    {
        return [
            [
                'title' => 'Pekerjaan tertunda',
                'description' => $delayedJobs > 0
                    ? $delayedJobs . ' pekerjaan perlu tindak lanjut segera.'
                    : 'Tidak ada pekerjaan tertunda untuk saat ini.',
                'icon' => 'clock',
                'status' => $delayedJobs > 0 ? 'Mendesak' : 'Aman',
                'status_class' => $delayedJobs > 0 ? 'danger' : 'success',
                'action' => 'Tinjau pekerjaan',
            ],
            [
                'title' => 'Pekerjaan aktif',
                'description' => $activeJobs . ' pekerjaan sedang dalam antrean operasional.',
                'icon' => 'activity',
                'status' => $activeJobs >= 10 ? 'Perhatian' : 'Normal',
                'status_class' => $activeJobs >= 10 ? 'warning' : 'info',
                'action' => 'Atur distribusi',
            ],
            [
                'title' => 'Pekerjaan selesai hari ini',
                'description' => $completedToday . ' pekerjaan sudah tervalidasi hari ini.',
                'icon' => 'check-circle',
                'status' => $completedToday > 0 ? 'Terjadwal' : 'Menunggu',
                'status_class' => $completedToday > 0 ? 'info' : 'neutral',
                'action' => 'Lihat rekap',
            ],
            [
                'title' => 'Sinkronisasi tim',
                'description' => 'Pastikan koordinasi lintas tim untuk menjaga ketepatan target harian.',
                'icon' => 'users',
                'status' => 'Menunggu',
                'status_class' => 'neutral',
                'action' => 'Buka koordinasi',
            ],
        ];
    }

    private function buildAdminOperasionalSchedules(): array
    {
        if (! Schema::hasTable('employee_activities')) {
            return [];
        }

        $query = DB::table('employee_activities');

        if (Schema::hasTable('employees')) {
            $query->leftJoin('employees', 'employees.id', '=', 'employee_activities.employee_id');
        }

        if (Schema::hasTable('service_orders')) {
            $query->leftJoin('service_orders', 'service_orders.id', '=', 'employee_activities.service_order_id');
        }

        $rows = $query
            ->select([
                'employee_activities.id',
                'employee_activities.activity_name',
                'employee_activities.status',
                'employee_activities.activity_date',
                'employee_activities.start_time',
                'employee_activities.end_time',
                'employee_activities.duration_minutes',
                'employees.full_name as employee_name',
                'service_orders.order_number as order_number',
            ])
            ->orderByDesc('employee_activities.activity_date')
            ->orderByDesc('employee_activities.id')
            ->limit(6)
            ->get();

        return $rows->map(static function (object $row): array {
            $status = match ((string) data_get($row, 'status')) {
                'verified' => 'Selesai',
                'rejected' => 'Tertunda',
                default => 'Terjadwal',
            };

            $progress = match ($status) {
                'Selesai' => 100,
                'Tertunda' => 10,
                default => 45,
            };

            $timeRange = '-';
            $startTime = data_get($row, 'start_time');
            $endTime = data_get($row, 'end_time');

            if ($startTime !== null || $endTime !== null) {
                $timeRange = substr((string) ($startTime ?? '--:--'), 0, 5) . ' - ' . substr((string) ($endTime ?? '--:--'), 0, 5);
            }

            return [
                'code' => 'OPR-' . str_pad((string) data_get($row, 'id'), 4, '0', STR_PAD_LEFT),
                'task' => (string) (data_get($row, 'activity_name') ?? 'Aktivitas operasional'),
                'category' => 'Operasional',
                'team' => 'Tim Operasional',
                'leader' => (string) (data_get($row, 'employee_name') ?? 'Belum ditentukan'),
                'time' => $timeRange,
                'location' => (string) (data_get($row, 'order_number') ?? '-'),
                'progress' => $progress,
                'status' => $status,
                'priority' => $status === 'Tertunda' ? 'Mendesak' : 'Normal',
            ];
        })->all();
    }

    private function buildAdminOperasionalTeamWorkloads(): array
    {
        if (! Schema::hasTable('employee_activities') || ! Schema::hasTable('employees')) {
            return [];
        }

        $rows = DB::table('employee_activities')
            ->join('employees', 'employees.id', '=', 'employee_activities.employee_id')
            ->select([
                'employees.full_name as name',
            ])
            ->selectRaw('COUNT(employee_activities.id) as active_jobs')
            ->groupBy('employees.full_name')
            ->orderByDesc('active_jobs')
            ->limit(4)
            ->get();

        return $rows->map(static function (object $row): array {
            $activeJobs = (int) (data_get($row, 'active_jobs') ?? 0);
            $load = max(10, min(100, $activeJobs * 18));

            return [
                'name' => (string) (data_get($row, 'name') ?? 'Tim Operasional'),
                'members' => 1,
                'active_jobs' => $activeJobs,
                'load' => $load,
                'status' => $load >= 90 ? 'Tinggi' : 'Normal',
            ];
        })->all();
    }

    private function buildAdminOperasionalActivities(): array
    {
        if (! Schema::hasTable('employee_activities')) {
            return [];
        }

        $rows = DB::table('employee_activities')
            ->select([
                'activity_name',
                'status',
                'updated_at',
            ])
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        return $rows->map(static function (object $row): array {
            $status = (string) (data_get($row, 'status') ?? 'submitted');

            $theme = match ($status) {
                'verified' => 'green',
                'rejected' => 'red',
                default => 'blue',
            };

            $icon = match ($status) {
                'verified' => 'check-circle',
                'rejected' => 'alert-triangle',
                default => 'calendar',
            };

            $title = match ($status) {
                'verified' => 'Pekerjaan berhasil diselesaikan',
                'rejected' => 'Pekerjaan perlu tindak lanjut',
                default => 'Pekerjaan dijadwalkan/diubah',
            };

            $updatedAt = data_get($row, 'updated_at');
            $timeLabel = 'Baru saja';

            if ($updatedAt !== null) {
                try {
                    $timeLabel = Carbon::parse((string) $updatedAt)->diffForHumans();
                } catch (Throwable) {
                    $timeLabel = 'Baru saja';
                }
            }

            return [
                'title' => $title,
                'description' => (string) (data_get($row, 'activity_name') ?? 'Aktivitas operasional diperbarui.'),
                'time' => $timeLabel,
                'icon' => $icon,
                'theme' => $theme,
            ];
        })->all();
    }

    private function countTodayByStatus(string $table, string $dateColumn, string $status): int
    {
        if (
            ! Schema::hasTable($table)
            || ! Schema::hasColumn($table, $dateColumn)
            || ! Schema::hasColumn($table, 'status')
        ) {
            return 0;
        }

        try {
            $query = DB::table($table)
                ->where('status', $status)
                ->whereDate($dateColumn, now()->toDateString());

            if (Schema::hasColumn($table, 'deleted_at')) {
                $query->whereNull('deleted_at');
            }

            return $query->count();
        } catch (Throwable) {
            return 0;
        }
    }

    private function getPerformancePeriods(): Collection
    {
        return $this->getTableRows(
            table: 'performance_periods',
            preferredOrderColumns: ['start_date', 'updated_at', 'id'],
            limit: 50,
            dateColumns: ['start_date', 'end_date', 'created_at', 'updated_at'],
        );
    }

    private function getPerformanceIndicators(): Collection
    {
        return $this->getTableRows(
            table: 'performance_indicators',
            preferredOrderColumns: ['updated_at', 'created_at', 'id'],
            limit: 12,
            dateColumns: ['created_at', 'updated_at'],
        );
    }

    private function getServiceCategories(): Collection
    {
        return $this->getTableRows(
            table: 'service_categories',
            preferredOrderColumns: ['updated_at', 'created_at', 'id'],
            limit: 50,
            dateColumns: ['created_at', 'updated_at', 'deleted_at'],
            onlyNotDeleted: true,
        );
    }

    private function getServices(): Collection
    {
        $services = $this->getTableRows(
        table: 'services',
        preferredOrderColumns: ['updated_at', 'created_at', 'id'],
        limit: 50,
        dateColumns: ['created_at', 'updated_at', 'deleted_at'],
        onlyNotDeleted: true,
    );

    if ($services->isEmpty() || ! Schema::hasTable('service_categories')) {
        return $services;
    }

    $categoryNames = DB::table('service_categories')
        ->whereIn('id', $services->pluck('service_category_id')->filter()->all())
        ->pluck('name', 'id');

    return $services->map(function (object $service) use ($categoryNames): object {
        $service->category_name = $categoryNames[(int) ($service->service_category_id ?? 0)] ?? 'Tanpa kategori';

        return $service;
    });
}

    private function buildBranchSummary(): array
    {
        if (! Schema::hasTable('branches')) {
            return $this->emptyStatusSummary();
        }

        $total    = $this->countRows('branches');
        $active   = $this->countByStatus('branches', 'active');
        $pending  = $this->countByStatus('branches', 'pending');
        $inactive = $this->countByStatus('branches', 'inactive');

        return [
            'total'               => $total,
            'active'              => $active,
            'pending'             => $pending,
            'inactive'            => $inactive,
            'active_percentage'   => $this->percentage($active, $total),
            'pending_percentage'  => $this->percentage($pending, $total),
            'inactive_percentage' => $this->percentage($inactive, $total),
        ];
    }

    private function buildPerformancePeriodSummary(): array
    {
        if (! Schema::hasTable('performance_periods')) {
            return [
                'total'     => 0,
                'draft'     => 0,
                'active'    => 0,
                'completed' => 0,
                'inactive'  => 0,
                'current'   => 0,
                'upcoming'  => 0,
                'expired'   => 0,
            ];
        }

        $total = $this->countRows('performance_periods');

        $summary = [
            'total'     => $total,
            'draft'     => $this->countByStatus('performance_periods', 'draft'),
            'active'    => $this->countByStatus('performance_periods', 'active'),
            'completed' => $this->countByStatus('performance_periods', 'completed'),
            'inactive'  => $this->countByStatus('performance_periods', 'inactive'),
            'current'   => 0,
            'upcoming'  => 0,
            'expired'   => 0,
        ];

        if (
            Schema::hasColumn('performance_periods', 'start_date')
            && Schema::hasColumn('performance_periods', 'end_date')
        ) {
            $today = now()->toDateString();

            $summary['current'] = DB::table('performance_periods')
                ->when(
                    Schema::hasColumn('performance_periods', 'deleted_at'),
                    fn(Builder $query): Builder => $query->whereNull('deleted_at')
                )
                ->whereDate('start_date', '<=', $today)
                ->whereDate('end_date', '>=', $today)
                ->count();

            $summary['upcoming'] = DB::table('performance_periods')
                ->when(
                    Schema::hasColumn('performance_periods', 'deleted_at'),
                    fn(Builder $query): Builder => $query->whereNull('deleted_at')
                )
                ->whereDate('start_date', '>', $today)
                ->count();

            $summary['expired'] = DB::table('performance_periods')
                ->when(
                    Schema::hasColumn('performance_periods', 'deleted_at'),
                    fn(Builder $query): Builder => $query->whereNull('deleted_at')
                )
                ->whereDate('end_date', '<', $today)
                ->count();
        }

        return $summary;
    }

    private function getCurrentPerformancePeriod(): ?object
    {
        if (! Schema::hasTable('performance_periods')) {
            return null;
        }

        $query = DB::table('performance_periods');

        if (Schema::hasColumn('performance_periods', 'deleted_at')) {
            $query->whereNull('deleted_at');
        }

        if (Schema::hasColumn('performance_periods', 'status')) {
            $query->where('status', 'active');
        }

        if (
            Schema::hasColumn('performance_periods', 'start_date')
            && Schema::hasColumn('performance_periods', 'end_date')
        ) {
            $today = now()->toDateString();

            $query
                ->whereDate('start_date', '<=', $today)
                ->whereDate('end_date', '>=', $today);
        }

        foreach (['start_date', 'updated_at', 'id'] as $column) {
            if (Schema::hasColumn('performance_periods', $column)) {
                $query->orderByDesc($column);
                break;
            }
        }

        $period = $query->first();

        if (! $period) {
            return null;
        }

        return $this->castDateColumns(
            $period,
            ['start_date', 'end_date', 'created_at', 'updated_at']
        );
    }

    private function buildIndicatorSummary(): array
    {
        if (! Schema::hasTable('performance_indicators')) {
            return [
                'total'               => 0,
                'active'              => 0,
                'inactive'            => 0,
                'active_percentage'   => 0.0,
                'total_active_weight' => 0.0,
                'average_weight'      => 0.0,
            ];
        }

        $total    = $this->countRows('performance_indicators');
        $active   = $this->countByStatus('performance_indicators', 'active');
        $inactive = $this->countByStatus('performance_indicators', 'inactive');

        $totalActiveWeight = 0.0;
        $averageWeight     = 0.0;

        if (Schema::hasColumn('performance_indicators', 'weight')) {
            $activeWeightQuery = DB::table('performance_indicators');

            if (Schema::hasColumn('performance_indicators', 'deleted_at')) {
                $activeWeightQuery->whereNull('deleted_at');
            }

            if (Schema::hasColumn('performance_indicators', 'status')) {
                $activeWeightQuery->where('status', 'active');
            }

            $totalActiveWeight = (float) $activeWeightQuery->sum('weight');

            $averageWeightQuery = DB::table('performance_indicators');

            if (Schema::hasColumn('performance_indicators', 'deleted_at')) {
                $averageWeightQuery->whereNull('deleted_at');
            }

            if (Schema::hasColumn('performance_indicators', 'status')) {
                $averageWeightQuery->where('status', 'active');
            }

            $averageWeight = (float) ($averageWeightQuery->avg('weight') ?? 0);
        }

        return [
            'total'               => $total,
            'active'              => $active,
            'inactive'            => $inactive,
            'active_percentage'   => $this->percentage($active, $total),
            'total_active_weight' => round($totalActiveWeight, 2),
            'average_weight'      => round($averageWeight, 2),
        ];
    }

    private function buildIndicatorWeightChart(): Collection
    {
        if (! Schema::hasTable('performance_indicators')) {
            return collect();
        }

        if (! Schema::hasColumn('performance_indicators', 'weight')) {
            return collect();
        }

        $query = DB::table('performance_indicators');

        if (Schema::hasColumn('performance_indicators', 'deleted_at')) {
            $query->whereNull('deleted_at');
        }

        if (Schema::hasColumn('performance_indicators', 'status')) {
            $query->where('status', 'active');
        }

        $query->orderByDesc('weight')->limit(10);

        return $query->get()->map(static function (object $row): array {
            return [
                'id'     => data_get($row, 'id'),
                'code'   => (string) (data_get($row, 'code') ?? '-'),
                'name'   => (string) (data_get($row, 'name') ?? 'Tanpa nama'),
                'weight' => (float) (data_get($row, 'weight') ?? 0),
            ];
        });
    }

    private function buildIndicatorDirectionSummary(): Collection
    {
        $directions = collect([
            'increase' => 'success',
            'decrease' => 'warning',
            'exact'    => 'info',
        ]);

        if (
            ! Schema::hasTable('performance_indicators')
            || ! Schema::hasColumn('performance_indicators', 'target_direction')
        ) {
            return $directions->map(
                static fn(string $class, string $key): array=> [
                    'key'        => $key,
                    'total'      => 0,
                    'percentage' => 0.0,
                    'class'      => $class,
                ]
            )->values();
        }

        $baseQuery = DB::table('performance_indicators');

        if (Schema::hasColumn('performance_indicators', 'deleted_at')) {
            $baseQuery->whereNull('deleted_at');
        }

        if (Schema::hasColumn('performance_indicators', 'status')) {
            $baseQuery->where('status', 'active');
        }

        $activeTotal = (clone $baseQuery)->count();

        return $directions->map(
            static function (string $class, string $key) use ($baseQuery, $activeTotal): array {
                $total = (clone $baseQuery)
                    ->where('target_direction', $key)
                    ->count();

                return [
                    'key'        => $key,
                    'total'      => $total,
                    'percentage' => $activeTotal > 0
                        ? round(($total / $activeTotal) * 100, 1)
                        : 0.0,
                    'class'      => $class,
                ];
            }
        )->values();
    }

    private function buildServiceCategorySummary(): array
    {
        if (! Schema::hasTable('service_categories')) {
            return [
                'total'             => 0,
                'active'            => 0,
                'inactive'          => 0,
                'trashed'           => 0,
                'active_percentage' => 0.0,
            ];
        }

        $total    = $this->countRows('service_categories');
        $active   = $this->countByStatus('service_categories', 'active');
        $inactive = $this->countByStatus('service_categories', 'inactive');
        $trashed  = 0;

        if (Schema::hasColumn('service_categories', 'deleted_at')) {
            $trashed = DB::table('service_categories')
                ->whereNotNull('deleted_at')
                ->count();
        }

        return [
            'total'             => $total,
            'active'            => $active,
            'inactive'          => $inactive,
            'trashed'           => $trashed,
            'active_percentage' => $this->percentage($active, $total),
        ];
    }

    private function buildServiceSummary(): array
    {
        if (! Schema::hasTable('services')) {
            return [
                'total'             => 0,
                'active'            => 0,
                'inactive'          => 0,
                'active_percentage' => 0.0,
                'average_price'     => 0.0,
            ];
        }

        $total        = $this->countRows('services');
    $active       = $this->countByStatus('services', 'active');
    $inactive     = $this->countByStatus('services', 'inactive');
    $averagePrice = (float) DB::table('services')
        ->whereNull('deleted_at')
        ->avg('base_price');

    return [
        'total'             => $total,
        'active'            => $active,
        'inactive'          => $inactive,
        'active_percentage' => $this->percentage($active, $total),
        'average_price'     => $averagePrice,
    ];
}

    private function buildInvoicePaymentLineChart(int $months = 6): array
    {
        $months = max(3, min(12, $months));

        $end = now()->startOfMonth();
        $start = (clone $end)->subMonths($months - 1);

        $periods = collect();
        for ($i = 0; $i < $months; $i++) {
            $period = (clone $start)->addMonths($i);
            $periods->push([
                'key'   => $period->format('Y-m'),
                'label' => $period->translatedFormat('M Y'),
            ]);
        }

        $invoiceMap = collect();
        $invoiceCountMap = collect();
        if (Schema::hasTable('invoices')) {
            $invoiceDateColumn = null;
            foreach (['invoice_date', 'created_at'] as $column) {
                if (Schema::hasColumn('invoices', $column)) {
                    $invoiceDateColumn = $column;
                    break;
                }
            }

            $invoiceAmountColumn = null;
            foreach (['total_amount', 'grand_total', 'amount'] as $column) {
                if (Schema::hasColumn('invoices', $column)) {
                    $invoiceAmountColumn = $column;
                    break;
                }
            }

            if ($invoiceDateColumn && $invoiceAmountColumn) {
                $invoiceMonthExpr = $this->monthKeyExpression($invoiceDateColumn);

                $query = DB::table('invoices')
                    ->whereDate($invoiceDateColumn, '>=', $start->toDateString())
                    ->selectRaw(
                        "{$invoiceMonthExpr} as month_key, " .
                        "COALESCE(SUM({$invoiceAmountColumn}), 0) as total_amount, COUNT(*) as total_items"
                    )
                    ->groupByRaw($invoiceMonthExpr);

                if (Schema::hasColumn('invoices', 'deleted_at')) {
                    $query->whereNull('deleted_at');
                }

                $rows = $query->get();
                $invoiceMap = $rows->mapWithKeys(
                    static fn(object $row): array => [(string) $row->month_key => (float) ($row->total_amount ?? 0)]
                );
                $invoiceCountMap = $rows->mapWithKeys(
                    static fn(object $row): array => [(string) $row->month_key => (int) ($row->total_items ?? 0)]
                );
            }
        }

        $paymentMap = collect();
        $paymentCountMap = collect();
        if (Schema::hasTable('payments')) {
            $paymentDateColumn = null;
            foreach (['payment_date', 'created_at'] as $column) {
                if (Schema::hasColumn('payments', $column)) {
                    $paymentDateColumn = $column;
                    break;
                }
            }

            $paymentAmountColumn = null;
            foreach (['amount', 'paid_amount'] as $column) {
                if (Schema::hasColumn('payments', $column)) {
                    $paymentAmountColumn = $column;
                    break;
                }
            }

            if ($paymentDateColumn && $paymentAmountColumn) {
                $paymentMonthExpr = $this->monthKeyExpression($paymentDateColumn);

                $query = DB::table('payments')
                    ->whereDate($paymentDateColumn, '>=', $start->toDateString())
                    ->selectRaw(
                        "{$paymentMonthExpr} as month_key, " .
                        "COALESCE(SUM({$paymentAmountColumn}), 0) as total_amount, COUNT(*) as total_items"
                    )
                    ->groupByRaw($paymentMonthExpr);

                if (Schema::hasColumn('payments', 'deleted_at')) {
                    $query->whereNull('deleted_at');
                }

                $rows = $query->get();
                $paymentMap = $rows->mapWithKeys(
                    static fn(object $row): array => [(string) $row->month_key => (float) ($row->total_amount ?? 0)]
                );
                $paymentCountMap = $rows->mapWithKeys(
                    static fn(object $row): array => [(string) $row->month_key => (int) ($row->total_items ?? 0)]
                );
            }
        }

        $labels = $periods->pluck('label')->all();
        $invoiceTotals = $periods
            ->map(static fn(array $period): float => (float) ($invoiceMap->get($period['key']) ?? 0.0))
            ->values()
            ->all();
        $paymentTotals = $periods
            ->map(static fn(array $period): float => (float) ($paymentMap->get($period['key']) ?? 0.0))
            ->values()
            ->all();
        $invoiceCounts = $periods
            ->map(static fn(array $period): int => (int) ($invoiceCountMap->get($period['key']) ?? 0))
            ->values()
            ->all();
        $paymentCounts = $periods
            ->map(static fn(array $period): int => (int) ($paymentCountMap->get($period['key']) ?? 0))
            ->values()
            ->all();

        return [
            'labels'            => $labels,
            'invoice_totals'    => $invoiceTotals,
            'payment_totals'    => $paymentTotals,
            'invoice_counts'    => $invoiceCounts,
            'payment_counts'    => $paymentCounts,
            'invoice_sum'       => array_sum($invoiceTotals),
            'payment_sum'       => array_sum($paymentTotals),
            'max_amount'        => max(1.0, (float) max(array_merge($invoiceTotals, $paymentTotals))),
        ];
    }

    private function monthKeyExpression(string $column): string
    {
        $driver = DB::connection()->getDriverName();
        $wrappedColumn = DB::connection()->getQueryGrammar()->wrap($column);

        return match ($driver) {
            'pgsql' => "to_char({$wrappedColumn}, 'YYYY-MM')",
            'sqlite' => "strftime('%Y-%m', {$wrappedColumn})",
            'sqlsrv' => "FORMAT({$wrappedColumn}, 'yyyy-MM')",
            default => "DATE_FORMAT({$wrappedColumn}, '%Y-%m')",
        };
    }

    private function buildDepartmentSummary(): Collection
    {
        if (! Schema::hasTable('departments')) {
            return collect();
        }

        $rows = $this->getTableRows(
            table: 'departments',
            preferredOrderColumns: ['name', 'id'],
            limit: 50,
        );

        return $rows->map(static fn(object $department): array=> [
            'id'     => data_get($department, 'id'),
            'code'   => data_get($department, 'code'),
            'name'   => data_get($department, 'name', 'Tanpa nama'),
            'status' => data_get($department, 'status', 'active'),
        ]);
    }

    private function buildRoleSummary(): Collection
    {
        if (! Schema::hasTable('roles')) {
            return collect();
        }

        $roles = DB::table('roles')
            ->orderBy('id')
            ->get();

        return $roles->map(function (object $role): array {
            $roleId   = data_get($role, 'id');
            $roleName = (string) (data_get($role, 'name') ?? 'Role');

            $users       = $this->countUsersForRole($roleId, $roleName, false);
            $activeUsers = $this->countUsersForRole($roleId, $roleName, true);

            return [
                'name'   => Str::of($roleName)
                    ->replace(['_', '-'], ' ')
                    ->title()
                    ->toString(),
                'users'  => $users,
                'active' => $activeUsers,
                'icon'   => $this->roleIcon($roleName),
            ];
        });
    }

    private function countUsersForRole(mixed $roleId, string $roleName, bool $onlyActive): int
    {
        if (! Schema::hasTable('users')) {
            return 0;
        }

        try {
            if (Schema::hasTable('model_has_roles')) {
                $query = DB::table('model_has_roles')
                    ->join('users', 'users.id', '=', 'model_has_roles.model_id')
                    ->where('model_has_roles.role_id', $roleId);

                if (Schema::hasColumn('model_has_roles', 'model_type')) {
                    $query->where('model_has_roles.model_type', 'App\\Models\\User');
                }

                $this->applyActiveUserFilter($query, $onlyActive);

                return $query->distinct('users.id')->count('users.id');
            }

            if (Schema::hasTable('role_user')) {
                $query = DB::table('role_user')
                    ->join('users', 'users.id', '=', 'role_user.user_id')
                    ->where('role_user.role_id', $roleId);

                $this->applyActiveUserFilter($query, $onlyActive);

                return $query->distinct('users.id')->count('users.id');
            }

            if (Schema::hasColumn('users', 'role_id')) {
                $query = DB::table('users')->where('role_id', $roleId);
                $this->applyActiveUserFilter($query, $onlyActive);

                return $query->count();
            }

            foreach (['role', 'role_name'] as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $query = DB::table('users')->where($column, $roleName);
                    $this->applyActiveUserFilter($query, $onlyActive);

                    return $query->count();
                }
            }
        } catch (Throwable) {
            return 0;
        }

        return 0;
    }

    private function applyActiveUserFilter(Builder $query, bool $onlyActive): void
    {
        if (! $onlyActive) {
            return;
        }

        if (Schema::hasColumn('users', 'status')) {
            $query->where('users.status', 'active');
            return;
        }

        if (Schema::hasColumn('users', 'is_active')) {
            $query->where('users.is_active', true);
        }
    }

    private function buildMonitoringPriorities(
        array $branchSummary,
        array $performancePeriodSummary,
        ?object $currentPerformancePeriod,
        array $indicatorSummary,
        array $serviceCategorySummary,
        string $positionsUrl,
        string $performancePeriodsUrl,
        string $performanceIndicatorsUrl,
        string $serviceCategoriesUrl,
    ): Collection {
        $priorities = collect();

        if ((int) ($branchSummary['inactive'] ?? 0) > 0) {
            $priorities->push([
                'title'        => 'Cabang tidak aktif perlu ditinjau',
                'description'  => (int) $branchSummary['inactive'] . ' cabang berstatus tidak aktif.',
                'status'       => 'Perlu tinjauan',
                'status_class' => 'danger',
                'icon'         => 'git-branch',
                'action'       => 'Tinjau struktur',
                'url'          => $positionsUrl,
            ]);
        }

        if ($currentPerformancePeriod === null) {
            $priorities->push([
                'title'        => 'Belum ada periode penilaian aktif',
                'description'  => 'Aktifkan periode yang sesuai agar proses monitoring kinerja dapat berjalan.',
                'status'       => 'Penting',
                'status_class' => 'warning',
                'icon'         => 'calendar',
                'action'       => 'Kelola periode',
                'url'          => $performancePeriodsUrl,
            ]);
        }

        if ((int) ($performancePeriodSummary['draft'] ?? 0) > 0) {
            $priorities->push([
                'title'        => 'Periode draft masih tersedia',
                'description'  => (int) $performancePeriodSummary['draft'] . ' periode masih berstatus draft.',
                'status'       => 'Draft',
                'status_class' => 'warning',
                'icon'         => 'edit-3',
                'action'       => 'Periksa periode',
                'url'          => $performancePeriodsUrl,
            ]);
        }

        if ((int) ($indicatorSummary['inactive'] ?? 0) > 0) {
            $priorities->push([
                'title'        => 'Indikator kinerja tidak aktif',
                'description'  => (int) $indicatorSummary['inactive'] . ' indikator saat ini berstatus tidak aktif.',
                'status'       => 'Monitoring',
                'status_class' => 'info',
                'icon'         => 'target',
                'action'       => 'Kelola indikator',
                'url'          => $performanceIndicatorsUrl,
            ]);
        }

        if ((int) ($serviceCategorySummary['inactive'] ?? 0) > 0) {
            $priorities->push([
                'title'        => 'Kategori layanan tidak aktif',
                'description'  => (int) $serviceCategorySummary['inactive'] . ' kategori layanan berstatus tidak aktif.',
                'status'       => 'Monitoring',
                'status_class' => 'info',
                'icon'         => 'layers',
                'action'       => 'Kelola kategori',
                'url'          => $serviceCategoriesUrl,
            ]);
        }

        if ((int) ($serviceCategorySummary['trashed'] ?? 0) > 0) {
            $priorities->push([
                'title'        => 'Kategori layanan berada di sampah',
                'description'  => (int) $serviceCategorySummary['trashed'] . ' kategori dapat dipulihkan atau dihapus permanen.',
                'status'       => 'Sampah',
                'status_class' => 'danger',
                'icon'         => 'trash-2',
                'action'       => 'Buka kategori layanan',
                'url'          => $serviceCategoriesUrl,
            ]);
        }

        return $priorities->take(6)->values();
    }

    private function buildSystemActivities(
        array $branchSummary,
        array $performancePeriodSummary,
        array $indicatorSummary,
        array $serviceCategorySummary,
    ): Collection {
        return collect([
            [
                'title'       => 'Dashboard siap digunakan',
                'description' => 'Data utama berhasil dimuat dari database.',
                'time'        => 'Baru saja',
                'icon'        => 'check-circle',
                'theme'       => 'green',
            ],
            [
                'title'       => 'Cabang terhubung',
                'description' => number_format((int) ($branchSummary['total'] ?? 0), 0, ',', '.') . ' cabang tersedia pada sistem.',
                'time'        => 'Hari ini',
                'icon'        => 'git-branch',
                'theme'       => 'blue',
            ],
            [
                'title'       => 'Indikator kinerja terhubung',
                'description' => number_format((int) ($indicatorSummary['total'] ?? 0), 0, ',', '.') . ' indikator tersedia pada sistem.',
                'time'        => 'Hari ini',
                'icon'        => 'target',
                'theme'       => 'purple',
            ],
            [
                'title'       => 'Periode penilaian terhubung',
                'description' => number_format((int) ($performancePeriodSummary['total'] ?? 0), 0, ',', '.') . ' periode penilaian tersedia.',
                'time'        => 'Hari ini',
                'icon'        => 'calendar',
                'theme'       => 'blue',
            ],
            [
                'title'       => 'Kategori layanan terhubung',
                'description' => number_format((int) ($serviceCategorySummary['total'] ?? 0), 0, ',', '.') .
                ' kategori tersedia: ' .
                number_format((int) ($serviceCategorySummary['active'] ?? 0), 0, ',', '.') .
                ' aktif dan ' .
                number_format((int) ($serviceCategorySummary['inactive'] ?? 0), 0, ',', '.') .
                ' tidak aktif.',
                'time'        => 'Hari ini',
                'icon'        => 'layers',
                'theme'       => 'blue',
            ],
        ]);
    }

    private function getTableRows(
        string $table,
        array $preferredOrderColumns = ['updated_at', 'created_at', 'id'],
        int $limit = 50,
        array $dateColumns = ['created_at', 'updated_at'],
        bool $onlyNotDeleted = true,
    ): Collection {
        if (! Schema::hasTable($table)) {
            return collect();
        }

        try {
            $query = DB::table($table);

            if ($onlyNotDeleted && Schema::hasColumn($table, 'deleted_at')) {
                $query->whereNull('deleted_at');
            }

            foreach ($preferredOrderColumns as $column) {
                if (Schema::hasColumn($table, $column)) {
                    $query->orderByDesc($column);
                    break;
                }
            }

            return $query
                ->limit($limit)
                ->get()
                ->map(fn(object $row): object => $this->castDateColumns($row, $dateColumns));
        } catch (Throwable) {
            return collect();
        }
    }

    private function castDateColumns(object $row, array $columns): object
    {
        foreach ($columns as $column) {
            if (! property_exists($row, $column)) {
                continue;
            }

            $value = $row->{$column};

            if ($value === null || $value instanceof Carbon) {
                continue;
            }

            try {
                $row->{$column} = Carbon::parse($value);
            } catch (Throwable) {
                // Biarkan nilai asli jika format tanggal tidak dapat diparse.
            }
        }

        return $row;
    }

    private function countRows(string $table): int
    {
        if (! Schema::hasTable($table)) {
            return 0;
        }

        try {
            $query = DB::table($table);

            if (Schema::hasColumn($table, 'deleted_at')) {
                $query->whereNull('deleted_at');
            }

            return $query->count();
        } catch (Throwable) {
            return 0;
        }
    }

    private function countByStatus(string $table, string $status): int
    {
        if (
            ! Schema::hasTable($table)
            || ! Schema::hasColumn($table, 'status')
        ) {
            return 0;
        }

        try {
            $query = DB::table($table)
                ->where('status', $status);

            if (Schema::hasColumn($table, 'deleted_at')) {
                $query->whereNull('deleted_at');
            }

            return $query->count();
        } catch (Throwable) {
            return 0;
        }
    }

    private function percentage(int $part, int $total): float
    {
        if ($total <= 0) {
            return 0.0;
        }

        return round(($part / $total) * 100, 1);
    }

    private function emptyStatusSummary(): array
    {
        return [
            'total'               => 0,
            'active'              => 0,
            'pending'             => 0,
            'inactive'            => 0,
            'active_percentage'   => 0.0,
            'pending_percentage'  => 0.0,
            'inactive_percentage' => 0.0,
        ];
    }

    private function routeUrl(string $routeName): string
    {
        return Route::has($routeName)
            ? route($routeName)
            : '#';
    }

    private function resolveActiveRoleName(mixed $user): string
    {
        if ($user === null) {

return 'Super Administrator';

        }

        try {

// Spatie Permission

            if (method_exists($user, 'getRoleNames')) {

                $role = $user->getRoleNames()->first();

if (is_string($role) && trim($role) !== '') {

    return trim($role);

                }
            }

        } catch (Throwable) {

        }

// Jika menggunakan kolom role langsung
foreach (
    [
        'active_role',
        'role',
        'role_name',
    ] as $field
) {

            $value = data_get($user, $field);

if (
    is_string($value)
    && trim($value) !== ''
) {

                return trim($value);

            }

        }

// Jika relation roles tersedia

        try {

            $roles = data_get($user, 'roles');

if (
    $roles instanceof Collection
    && $roles->isNotEmpty()
) {



$roleName = data_get(
    $roles->first(),
    'name'
);

if (
    is_string($roleName)
    && $roleName !== ''
) {

    return trim($roleName);

                }

            }

        } catch (Throwable) {

        }

return 'Super Administrator';

    }

    private function roleIcon(string $roleName): string
    {
        $roleName = Str::lower($roleName);

        return match (true) {
            Str::contains($roleName, ['super', 'admin'])              => 'shield',
            Str::contains($roleName, ['direktur', 'director'])        => 'briefcase',
            Str::contains($roleName, ['hrd', 'human resource'])       => 'users',
            Str::contains($roleName, ['manager', 'leader', 'kepala']) => 'user-check',
            default                                                   => 'user',
        };
    }

    private function resolveDashboardView(): string
    {
$role = Str::lower(
    $this->resolveActiveRoleName(Auth::user())
);

// normalisasi role
$role = str_replace(
    [' ', '-', '_'],
    '',
    $role
);

return match (true) {

    Str::contains($role, 'superadministrator')
    || Str::contains($role, 'superadmin')
    || Str::contains($role, 'super')         => 'dashboards.super-admin',

    Str::contains($role, 'direktur')         => 'dashboards.direktur-utama',

    Str::contains($role, 'hrd')              => 'dashboards.hrd',

    Str::contains($role, 'manager')          => 'dashboards.manager-departemen',

    Str::contains($role, 'karyawan')         => 'dashboards.karyawan',

    Str::contains($role, 'adminpelayanan')   => 'dashboards.admin-pelayanan',

    Str::contains($role, 'adminoperasional') => 'dashboards.admin-operasional',

    Str::contains($role, 'auditor')          => 'dashboards.auditor',

    Str::contains($role, 'keuangan')         => 'dashboards.keuangan',

    default
                                             => 'dashboards.super-admin',
};
}






}

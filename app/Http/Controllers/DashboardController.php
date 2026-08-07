<?php

declare (strict_types = 1);

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Throwable;

class DashboardController extends Controller
{
    public function index(): ViewContract
    {
        $currentUser = auth()->user();

        $activeRoleName  = $this->resolveActiveRoleName($currentUser);
        $activeRoleLabel = Str::of($activeRoleName)
            ->replace('_', ' ')
            ->upper()
            ->toString();

        $positions             = $this->getPositions();
        $performancePeriods    = $this->getPerformancePeriods();
        $performanceIndicators = $this->getPerformanceIndicators();
        $serviceCategories     = $this->getServiceCategories();

        $branchSummary            = $this->buildBranchSummary();
        $performancePeriodSummary = $this->buildPerformancePeriodSummary();
        $currentPerformancePeriod = $this->getCurrentPerformancePeriod();

        $indicatorSummary          = $this->buildIndicatorSummary();
        $indicatorWeightChart      = $this->buildIndicatorWeightChart();
        $indicatorDirectionSummary = $this->buildIndicatorDirectionSummary();

        $serviceCategorySummary = $this->buildServiceCategorySummary();
        $departmentSummary      = $this->buildDepartmentSummary();
        $roleSummary            = $this->buildRoleSummary();

        $totalActivePositions = $this->countByStatus('positions', 'active');

        $usersUrl                 = $this->routeUrl('super-admin.users.index');
        $positionsUrl             = $this->routeUrl('super-admin.positions.index');
        $performancePeriodsUrl    = $this->routeUrl('super-admin.performance-periods.index');
        $performanceIndicatorsUrl = $this->routeUrl('super-admin.performance-indicators.index');
        $serviceCategoriesUrl     = $this->routeUrl('super-admin.service-categories.index');

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

        return view($this->resolveDashboardView(), [
            'activeRoleName'            => $activeRoleName,
            'activeRoleLabel'           => $activeRoleLabel,

            'usersUrl'                  => $usersUrl,
            'positionsUrl'              => $positionsUrl,
            'performancePeriodsUrl'     => $performancePeriodsUrl,
            'performanceIndicatorsUrl'  => $performanceIndicatorsUrl,
            'serviceCategoriesUrl'      => $serviceCategoriesUrl,

            'positions'                 => $positions,
            'performancePeriods'        => $performancePeriods,
            'performanceIndicators'     => $performanceIndicators,
            'serviceCategories'         => $serviceCategories,

            'branchSummary'             => $branchSummary,
            'branchAngle'               => $branchAngle,

            'performancePeriodSummary'  => $performancePeriodSummary,
            'currentPerformancePeriod'  => $currentPerformancePeriod,

            'indicatorSummary'          => $indicatorSummary,
            'indicatorWeightChart'      => $indicatorWeightChart,
            'indicatorDirectionSummary' => $indicatorDirectionSummary,
            'indicatorAngle'            => $indicatorAngle,

            'serviceCategorySummary'    => $serviceCategorySummary,
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
            return 'super_admin';
        }

        try {
            if (method_exists($user, 'getRoleNames')) {
                $role = $user->getRoleNames()->first();

                if (is_string($role) && $role !== '') {
                    return $role;
                }
            }
        } catch (Throwable) {
            // Fallback ke kolom/relation lain.
        }

        foreach (['active_role', 'role', 'role_name'] as $field) {
            $value = data_get($user, $field);

            if (is_string($value) && trim($value) !== '') {
                return trim($value);
            }
        }

        try {
            $roles = data_get($user, 'roles');

            if ($roles instanceof Collection && $roles->isNotEmpty()) {
                $roleName = data_get($roles->first(), 'name');

                if (is_string($roleName) && $roleName !== '') {
                    return $roleName;
                }
            }
        } catch (Throwable) {
            // Gunakan default.
        }

        return 'super_admin';
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
        foreach (
            [
                'dashboard.super-admin',
                'dashboards.super-admin',
                'super-admin',
            ] as $viewName
        ) {
            if (View::exists($viewName)) {
                return $viewName;
            }
        }

        return 'dashboard.super-admin';
    }
}

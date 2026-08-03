<?php

declare (strict_types = 1);

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Department;
use App\Models\PerformancePeriod;
use App\Models\Position;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Illuminate\View\View as ViewContract;

class DashboardController extends Controller
{
    private const DASHBOARD_VIEWS = [
        'super_admin'        => 'dashboards.super-admin',
        'direktur_utama'     => 'dashboards.direktur-utama',
        'hrd_manager'        => 'dashboards.hrd',
        'manager_departemen' => 'dashboards.manager-departemen',
        'karyawan'           => 'dashboards.karyawan',
        'admin_pelayanan'    => 'dashboards.admin-pelayanan',
        'admin_operasional'  => 'dashboards.admin-operasional',
        'finance_staff'      => 'dashboards.keuangan',
        'auditor_internal'   => 'dashboards.auditor',
    ];

    public function index(
        Request $request
    ): ViewContract | RedirectResponse {
        $user = $request->user();

        if (! $user instanceof User) {
            return redirect()
                ->route('login')
                ->with(
                    'error',
                    'Silakan login kembali.'
                );
        }

        if (! $user->isActive()) {
            $this->logoutSession($request);

            return redirect()
                ->route('login')
                ->with(
                    'error',
                    'Akun tidak aktif.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | ROLE AKTIF
        |--------------------------------------------------------------------------
        */

        $activeRole = null;
        $roleId     = session('active_role_id');

        if ($roleId) {
            $activeRole = $user
                ->roles()
                ->where('roles.id', $roleId)
                ->first();
        }

        if (! $activeRole) {
            $activeRole = $user
                ->roles()
                ->first();
        }

        abort_if(
            ! $activeRole,
            403,
            'User belum memiliki role.'
        );

        /*
         * Gunakan slug jika tersedia karena lebih stabil untuk routing role.
         * Jika slug belum tersedia, gunakan name.
         */
        $roleSource = filled($activeRole->slug ?? null)
            ? (string) $activeRole->slug
            : (string) $activeRole->name;

        $roleName = $this->normalizeRole($roleSource);

        $dashboard = self::DASHBOARD_VIEWS[$roleName] ?? null;

        abort_if(
            ! $dashboard,
            403,
            'Dashboard role belum tersedia.'
        );

        /*
        |--------------------------------------------------------------------------
        | STATISTIK CABANG
        |--------------------------------------------------------------------------
        */

        $totalBranch = Branch::query()->count();

        $activeBranch = Branch::query()
            ->where('status', 1)
            ->count();

        $inactiveBranch = Branch::query()
            ->where('status', 0)
            ->count();

        $pendingBranch = Branch::query()
            ->where('approval_status', 'pending')
            ->count();

        $activePercentage   = 0.0;
        $inactivePercentage = 0.0;
        $pendingPercentage  = 0.0;

        if ($totalBranch > 0) {
            $activePercentage = round(
                ($activeBranch / $totalBranch) * 100,
                1
            );

            $inactivePercentage = round(
                ($inactiveBranch / $totalBranch) * 100,
                1
            );

            $pendingPercentage = round(
                ($pendingBranch / $totalBranch) * 100,
                1
            );
        }

        $branchSummary = [
            'total'               => $totalBranch,
            'active'              => $activeBranch,
            'inactive'            => $inactiveBranch,
            'pending'             => $pendingBranch,
            'active_percentage'   => $activePercentage,
            'inactive_percentage' => $inactivePercentage,
            'pending_percentage'  => $pendingPercentage,
        ];

        $branchAngle = ($activePercentage / 100) * 360;

        /*
        |--------------------------------------------------------------------------
        | JABATAN BERDASARKAN DEPARTEMEN
        |--------------------------------------------------------------------------
        |
        | Menggantikan data "Kepuasan Berdasarkan Kanal" yang sebelumnya
        | masih menggunakan data contoh.
        |
        */

        $totalActivePositions = Position::query()
            ->where('status', 'active')
            ->count();

        $departmentIcons = [
            'briefcase',
            'layers',
            'grid',
            'users',
            'folder',
            'building',
        ];

        $departmentClasses = [
            'primary',
            'info',
            'success',
            'warning',
            'danger',
        ];

        $departmentSummary = Department::query()
            ->where('status', 'active')
            ->withCount([
                'positions as positions_count' => function ($query): void {
                    $query->where('status', 'active');
                },
            ])
            ->orderByDesc('positions_count')
            ->orderBy('name')
            ->limit(6)
            ->get()
            ->values()
            ->map(
                function (
                    Department $department,
                    int $index
                ) use (
                    $totalActivePositions,
                    $departmentIcons,
                    $departmentClasses
                ): array {
                    $positionCount = (int) $department->positions_count;

                    $percentage = $totalActivePositions > 0
                        ? (int) round(
                        ($positionCount / $totalActivePositions) * 100
                    )
                        : 0;

                    return [
                        'id'         => $department->id,
                        'code'       => $department->code,
                        'name'       => $department->name,
                        'positions'  => $positionCount,
                        'percentage' => $percentage,
                        'icon'       => $departmentIcons[
                            $index % count($departmentIcons)
                        ],
                        'class'      => $departmentClasses[
                            $index % count($departmentClasses)
                        ],
                    ];
                }
            );

        /*
        |--------------------------------------------------------------------------
        | DAFTAR JABATAN TERBARU
        |--------------------------------------------------------------------------
        |
        | Data ini digunakan oleh tabel "Data Jabatan" pada dashboard.
        | Relasi department dimuat sekaligus agar tidak terjadi N+1 query.
        |
        */

        $positions = Position::query()
            ->select([
                'id',
                'department_id',
                'code',
                'name',
                'level',
                'description',
                'status',
                'created_at',
                'updated_at',
            ])
            ->with([
                'department:id,code,name',
            ])
            ->latest('updated_at')
            ->limit(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | PERIODE PENILAIAN KINERJA
        |--------------------------------------------------------------------------
        |
        | Data diambil langsung dari tabel performance_periods:
        | id, name, start_date, end_date, period_type, status,
        | created_at, dan updated_at.
        |
        */

        $today = now()->toDateString();

        $performancePeriodSummary = [
            'total'     => PerformancePeriod::query()->count(),

            'draft'     => PerformancePeriod::query()
                ->where('status', 'draft')
                ->count(),

            'active'    => PerformancePeriod::query()
                ->where('status', 'active')
                ->count(),

            'completed' => PerformancePeriod::query()
                ->where('status', 'completed')
                ->count(),

            'inactive'  => PerformancePeriod::query()
                ->where('status', 'inactive')
                ->count(),

            /*
             * Periode aktif yang tanggalnya mencakup hari ini.
             */
            'current'   => PerformancePeriod::query()
                ->where('status', 'active')
                ->whereDate('start_date', '<=', $today)
                ->whereDate('end_date', '>=', $today)
                ->count(),

            /*
             * Periode yang belum dimulai.
             */
            'upcoming'  => PerformancePeriod::query()
                ->whereDate('start_date', '>', $today)
                ->count(),

            /*
             * Periode yang tanggal akhirnya telah lewat.
             */
            'expired'   => PerformancePeriod::query()
                ->whereDate('end_date', '<', $today)
                ->count(),
        ];

        $currentPerformancePeriod = PerformancePeriod::query()
            ->where('status', 'active')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->orderBy('end_date')
            ->orderBy('start_date')
            ->first();

        $performancePeriods = PerformancePeriod::query()
            ->select([
                'id',
                'name',
                'start_date',
                'end_date',
                'period_type',
                'status',
                'created_at',
                'updated_at',
            ])
            ->orderByDesc('start_date')
            ->orderByDesc('updated_at')
            ->limit(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | PENGGUNA BERDASARKAN ROLE
        |--------------------------------------------------------------------------
        */

        $roleIcons = [
            'super_admin'        => 'shield',
            'direktur_utama'     => 'award',
            'hrd_manager'        => 'users',
            'manager_departemen' => 'briefcase',
            'karyawan'           => 'user',
            'admin_pelayanan'    => 'headphones',
            'admin_operasional'  => 'settings',
            'finance_staff'      => 'credit-card',
            'auditor_internal'   => 'check-square',
        ];

        /*
         * Tabel roles dan users pada database aktif tidak mempunyai
         * kolom is_active. Karena itu, hanya hitung user yang terhubung
         * dengan role dan belum terkena soft delete.
         */
        $roleSummary = Role::query()
            ->withCount('users')
            ->orderByDesc('users_count')
            ->orderBy('name')
            ->limit(6)
            ->get()
            ->map(
                function (Role $role) use ($roleIcons): array {
                    $normalizedRole = $this->normalizeRole(
                        filled($role->slug ?? null)
                            ? (string) $role->slug
                            : (string) $role->name
                    );

                    $usersCount = (int) $role->users_count;

                    return [
                        'id'     => $role->id,
                        'slug'   => $normalizedRole,
                        'name'   => $role->name,
                        'users'  => $usersCount,

                        /*
                         * Nilai active dipertahankan agar Blade lama
                         * tidak mengalami undefined index.
                         */
                        'active' => $usersCount,

                        'icon'   => $roleIcons[$normalizedRole] ?? 'user',
                    ];
                }
            );

        /*
        |--------------------------------------------------------------------------
        | URL MENU DASHBOARD
        |--------------------------------------------------------------------------
        */

        $positionsUrl = Route::has('super-admin.positions.index')
            ? route('super-admin.positions.index')
            : '#';

        $usersUrl = Route::has('super-admin.users.index')
            ? route('super-admin.users.index')
            : '#';

        $performancePeriodsUrl = Route::has(
            'super-admin.performance-periods.index'
        )
            ? route('super-admin.performance-periods.index')
            : '#';

        /*
        |--------------------------------------------------------------------------
        | PRIORITAS MONITORING
        |--------------------------------------------------------------------------
        |
        | Variabel ini digunakan oleh bagian "Prioritas Monitoring"
        | pada dashboard super admin.
        |
        */

        $monitoringPriorities = [];

        if ($pendingBranch > 0) {
            $monitoringPriorities[] = [
                'icon'         => 'git-branch',
                'title'        => 'Persetujuan cabang tertunda',
                'description'  => sprintf(
                    '%d cabang masih menunggu proses persetujuan.',
                    $pendingBranch
                ),
                'status'       => 'Perlu tindakan',
                'status_class' => 'warning',
                'action'       => 'Periksa data cabang',
                'url'          => Route::has('super-admin.branches.index')
                    ? route('super-admin.branches.index')
                    : '#',
            ];
        }

        if (! $currentPerformancePeriod) {
            $monitoringPriorities[] = [
                'icon'         => 'calendar',
                'title'        => 'Belum ada periode penilaian aktif',
                'description'  => 'Tidak ditemukan periode berstatus aktif yang mencakup tanggal hari ini.',
                'status'       => 'Prioritas',
                'status_class' => 'danger',
                'action'       => 'Kelola periode penilaian',
                'url'          => $performancePeriodsUrl,
            ];
        } else {
            $daysRemaining = now()
                ->startOfDay()
                ->diffInDays(
                    $currentPerformancePeriod->end_date,
                    false
                );

            if ($daysRemaining >= 0 && $daysRemaining <= 7) {
                $monitoringPriorities[] = [
                    'icon'         => 'clock',
                    'title'        => 'Periode penilaian segera berakhir',
                    'description'  => sprintf(
                        'Periode "%s" berakhir dalam %d hari.',
                        $currentPerformancePeriod->name,
                        $daysRemaining
                    ),
                    'status'       => 'Segera',
                    'status_class' => 'warning',
                    'action'       => 'Lihat periode',
                    'url'          => Route::has(
                        'super-admin.performance-periods.show'
                    )
                        ? route(
                        'super-admin.performance-periods.show',
                        $currentPerformancePeriod
                    )
                        : $performancePeriodsUrl,
                ];
            }
        }

        if ($performancePeriodSummary['draft'] > 0) {
            $monitoringPriorities[] = [
                'icon'         => 'edit-3',
                'title'        => 'Periode masih berstatus draft',
                'description'  => sprintf(
                    '%d periode penilaian belum diaktifkan.',
                    $performancePeriodSummary['draft']
                ),
                'status'       => 'Draft',
                'status_class' => 'info',
                'action'       => 'Tinjau periode',
                'url'          => $performancePeriodsUrl,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | SIMPAN ROLE AKTIF
        |--------------------------------------------------------------------------
        */

        session([
            'active_role_id'   => $activeRole->id,
            'active_role_name' => $roleName,
            'active_role'      => $roleName,
        ]);

        abort_unless(
            View::exists($dashboard),
            500,
            "Dashboard {$dashboard} tidak ditemukan."
        );

        return view($dashboard, [
            'user'                     => $user,
            'activeRole'               => $activeRole,
            'activeRoleName'           => $roleName,
            'activeRoleId'             => $activeRole->id,

            'activeRoleLabel'          => Str::of($roleName)
                ->replace('_', ' ')
                ->title()
                ->toString(),

            'branchSummary'            => $branchSummary,
            'branchAngle'              => $branchAngle,

            /*
             * Data dashboard berdasarkan database.
             */
            'departmentSummary'        => $departmentSummary,
            'positions'                => $positions,
            'roleSummary'              => $roleSummary,
            'totalActivePositions'     => $totalActivePositions,

            'performancePeriods'       => $performancePeriods,
            'performancePeriodSummary' => $performancePeriodSummary,
            'currentPerformancePeriod' => $currentPerformancePeriod,
            'monitoringPriorities'     => $monitoringPriorities,

            /*
             * URL tombol pada dashboard.
             */
            'positionsUrl'             => $positionsUrl,
            'usersUrl'                 => $usersUrl,
            'performancePeriodsUrl'    => $performancePeriodsUrl,
        ]);
    }

    private function normalizeRole(string $role): string
    {
        $role = Str::of($role)
            ->lower()
            ->trim()
            ->replace(
                [
                    '-',
                    ' ',
                ],
                '_'
            )
            ->toString();

        return match ($role) {
            'superadmin',
            'super_admin'        => 'super_admin',

            'executive',
            'direktur',
            'direktur_utama'     => 'direktur_utama',

            'hr',
            'hrd',
            'hrd_manager'        => 'hrd_manager',

            'manager',
            'manager_departemen' => 'manager_departemen',

            'pegawai',
            'employee',
            'user',
            'karyawan'           => 'karyawan',

            'pelayanan',
            'admin_pelayanan'    => 'admin_pelayanan',

            'operasional',
            'admin_operasional'  => 'admin_operasional',

            'finance',
            'keuangan',
            'finance_staff'      => 'finance_staff',

            'audit',
            'auditor',
            'auditor_internal'   => 'auditor_internal',

            default              => $role,
        };
    }

    private function logoutSession(Request $request): void
    {
        Auth::logout();

        $request
            ->session()
            ->forget([
                'active_role_id',
                'active_role_name',
                'active_role',
            ]);

        $request
            ->session()
            ->invalidate();

        $request
            ->session()
            ->regenerateToken();
    }
}

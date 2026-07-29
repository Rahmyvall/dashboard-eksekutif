<?php

declare (strict_types = 1);

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    public function index(Request $request): ViewContract | RedirectResponse
    {

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
        | ROLE
        |--------------------------------------------------------------------------
        */

        $activeRole = null;

        $roleId = session('active_role_id');

        if ($roleId) {

            $activeRole = $user
                ->roles()
                ->where(
                    'roles.id',
                    $roleId
                )
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

        $roleName = $this->normalizeRole(
            $activeRole->name
        );

        $dashboard = self::DASHBOARD_VIEWS[$roleName] ?? null;

        abort_if(
            ! $dashboard,
            403,
            'Dashboard role belum tersedia.'
        );

        /*
        |--------------------------------------------------------------------------
        | DATA CABANG
        |--------------------------------------------------------------------------
        */

        $totalBranch = Branch::count();

        $activeBranch = Branch::where(
            'status',
            1
        )->count();

        $inactiveBranch = Branch::where(
            'status',
            0
        )->count();

        $pendingBranch = Branch::where(
            'approval_status',
            'pending'
        )->count();

        $activePercentage   = 0;
        $inactivePercentage = 0;
        $pendingPercentage  = 0;

        if ($totalBranch > 0) {

            $activePercentage =
                round(
                ($activeBranch / $totalBranch) * 100,
                1
            );

            $inactivePercentage =
                round(
                ($inactiveBranch / $totalBranch) * 100,
                1
            );

            $pendingPercentage =
                round(
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

        $branchAngle =
            ($activePercentage / 100) * 360;

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

            'user'            => $user,

            'activeRole'      => $activeRole,

            'activeRoleName'  => $roleName,

            'activeRoleId'    => $activeRole->id,

            'activeRoleLabel' => Str::of($roleName)
                ->replace('_', ' ')
                ->title()
                ->toString(),

            'branchSummary'   => $branchSummary,

            'branchAngle'     => $branchAngle,

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

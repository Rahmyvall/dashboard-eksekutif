<?php

declare (strict_types = 1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Illuminate\View\View as ViewContract;

class DashboardController extends Controller
{
    /**
     * Mapping nama role canonical ke view dashboard.
     *
     * @var array<string, string>
     */
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

    /**
     * Dashboard berdasarkan role aktif.
     */
    public function index(Request $request): ViewContract | RedirectResponse
    {
        /*
        |--------------------------------------------------------------------------
        | User Login
        |--------------------------------------------------------------------------
        */

        $user = $request->user();

        if (! $user instanceof User) {
            return redirect()
                ->route('login')
                ->with('error', 'Silakan login kembali.');
        }

        /*
        |--------------------------------------------------------------------------
        | Cek Status User
        |--------------------------------------------------------------------------
        */

        if (! $user->isActive()) {
            $this->logoutSession($request);

            return redirect()
                ->route('login')
                ->with('error', 'Akun tidak aktif.');
        }

        /*
        |--------------------------------------------------------------------------
        | Tentukan Role Aktif
        |--------------------------------------------------------------------------
        |
        | Prioritas:
        | 1. Role yang dikirim oleh route melalui defaults('dashboard_role', ...)
        | 2. Role yang tersimpan di session
        | 3. Role pertama milik user
        |
        */

        $routeRoleName = $request->route('dashboard_role');

        $activeRole = null;

        if (is_string($routeRoleName) && trim($routeRoleName) !== '') {
            $canonicalRouteRole = $this->normalizeRoleName($routeRoleName);

            $activeRole = $user
                ->roles()
                ->whereRaw('LOWER(roles.name) = ?', [$canonicalRouteRole])
                ->first();

            abort_if(
                $activeRole === null,
                403,
                'Role tidak dimiliki user.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Gunakan Role dari Session
        |--------------------------------------------------------------------------
        */

        if ($activeRole === null) {
            $roleId = $request->session()->get('active_role_id');

            if ($roleId !== null) {
                $activeRole = $user
                    ->roles()
                    ->where('roles.id', $roleId)
                    ->first();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Fallback ke Role Pertama User
        |--------------------------------------------------------------------------
        */

        if ($activeRole === null) {
            $activeRole = $user
                ->roles()
                ->orderBy('roles.id')
                ->first();
        }

        abort_if(
            $activeRole === null,
            403,
            'User belum memiliki role.'
        );

        /*
        |--------------------------------------------------------------------------
        | Normalisasi Nama Role
        |--------------------------------------------------------------------------
        */

        $canonicalRoleName = $this->normalizeRoleName(
            (string) $activeRole->name
        );

        /*
        |--------------------------------------------------------------------------
        | Dashboard Mapping
        |--------------------------------------------------------------------------
        */

        $dashboard = self::DASHBOARD_VIEWS[$canonicalRoleName] ?? null;

        abort_if(
            $dashboard === null,
            403,
            'Role Anda belum memiliki akses dashboard.'
        );

        /*
        |--------------------------------------------------------------------------
        | Simpan Session Role Canonical
        |--------------------------------------------------------------------------
        */

        $request->session()->put([
            'active_role_id'   => $activeRole->id,
            'active_role_name' => $canonicalRoleName,
            'active_role'      => $canonicalRoleName,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Cek View
        |--------------------------------------------------------------------------
        */

        abort_unless(
            View::exists($dashboard),
            500,
            "Dashboard {$dashboard} belum tersedia."
        );

        return view($dashboard, [
            'user'            => $user,
            'activeRole'      => $activeRole,
            'activeRoleName'  => $canonicalRoleName,
            'activeRoleId'    => $activeRole->id,
            'activeRoleLabel' => Str::of($canonicalRoleName)
                ->replace('_', ' ')
                ->title()
                ->toString(),
        ]);
    }

    /**
     * Menyamakan alias role lama dengan nama role canonical.
     */
    private function normalizeRoleName(string $roleName): string
    {
        $normalized = Str::of($roleName)
            ->trim()
            ->lower()
            ->replace(['-', ' '], '_')
            ->replaceMatches('/_+/', '_')
            ->toString();

        return match ($normalized) {
            'superadmin',
            'super_admin'        => 'super_admin',

            'executive',
            'direktur',
            'direktur_utama'     => 'direktur_utama',

            'hr',
            'hrd',
            'hrd_manager',
            'human_resource',
            'human_resources'    => 'hrd_manager',

            'manager',
            'manager_department',
            'manager_departemen' => 'manager_departemen',

            'pegawai',
            'employee',
            'user',
            'karyawan'           => 'karyawan',

            'pelayanan',
            'admin_service',
            'admin_pelayanan'    => 'admin_pelayanan',

            'operasional',
            'admin_operation',
            'admin_operasional'  => 'admin_operasional',

            'finance',
            'financial',
            'keuangan',
            'finance_staff'      => 'finance_staff',

            'audit',
            'auditor',
            'auditor_internal'   => 'auditor_internal',

            default              => $normalized,
        };
    }

    /**
     * Logout dan hapus session autentikasi.
     */
    private function logoutSession(Request $request): void
    {
        Auth::logout();

        $request->session()->forget([
            'active_role_id',
            'active_role_name',
            'active_role',
        ]);

        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}

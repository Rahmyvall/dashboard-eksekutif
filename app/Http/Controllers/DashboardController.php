<?php

declare (strict_types = 1);

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\View\View as ViewContract;

class DashboardController extends Controller
{

    /**
     * Dashboard berdasarkan role aktif
     */
    public function index(
        Request $request
    ): ViewContract | RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | User Login
        |--------------------------------------------------------------------------
        */

        $user = $request->user();

        if (! $user instanceof User) {

            return redirect()
                ->route('login')
                ->with(
                    'error',
                    'Silakan login kembali.'
                );

        }

        /*
        |--------------------------------------------------------------------------
        | Cek status user
        |--------------------------------------------------------------------------
        */

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
        | Ambil role aktif session
        |--------------------------------------------------------------------------
        */

        $roleId = session(
            'active_role_id'
        );

        $roleName = session(
            'active_role_name'
        );

        /*
        |--------------------------------------------------------------------------
        | Jika session kosong ambil role pertama user
        |--------------------------------------------------------------------------
        */

        if (! $roleId || ! $roleName) {

            $role = $user
                ->roles()
                ->first();

            if (! $role) {

                abort(
                    403,
                    'User belum memiliki role.'
                );

            }

            session([

                'active_role_id'   => $role->id,

                'active_role_name' => $role->name,

            ]);

            $roleId = $role->id;

            $roleName = $role->name;

        }

        /*
        |--------------------------------------------------------------------------
        | Validasi user mempunyai role tersebut
        |--------------------------------------------------------------------------
        */

        $activeRole = $user
            ->roles()
            ->where(
                'roles.id',
                $roleId
            )
            ->first();

        if (! $activeRole) {

            abort(
                403,
                'Role tidak dimiliki user.'
            );

        }

        /*
        |--------------------------------------------------------------------------
        | Dashboard Mapping
        |--------------------------------------------------------------------------
        */

        $dashboard = match (
            strtolower($activeRole->name)
        ) {

            'super_admin'        =>

            'dashboards.super-admin',

            'executive',
            'direktur_utama'     =>

            'dashboards.direktur-utama',

            'hr',
            'hrd'                =>

            'dashboards.hrd',

            'manager_departemen' =>

            'dashboards.manager-departemen',

            'karyawan',
            'user'               =>

            'dashboards.karyawan',

            'admin_pelayanan'    =>

            'dashboards.admin-pelayanan',

            'admin_operasional'  =>

            'dashboards.admin-operasional',

            'finance',
            'keuangan'           =>

            'dashboards.keuangan',

            'auditor'            =>

            'dashboards.auditor',

            default              => null

        };

        /*
        |--------------------------------------------------------------------------
        | Role Tidak Dikenal
        |--------------------------------------------------------------------------
        */

        if (! $dashboard) {

            abort(
                403,
                'Role Anda belum memiliki akses dashboard.'
            );

        }

        /*
        |--------------------------------------------------------------------------
        | Cek View
        |--------------------------------------------------------------------------
        */

        if (! View::exists($dashboard)) {

            abort(
                500,
                "Dashboard {$dashboard} belum tersedia."
            );

        }

        return view(
            $dashboard,
            [

                'user'            => $user,

                'activeRole'      => $activeRole,

                'activeRoleName'  => $activeRole->name,

                'activeRoleId'    => $activeRole->id,

                'activeRoleLabel' =>
                ucwords(
                    str_replace(
                        '_',
                        ' ',
                        $activeRole->name
                    )
                ),

            ]
        );

    }

    /**
     * Logout session
     */
    private function logoutSession(
        Request $request
    ): void {

        Auth::logout();

        $request
            ->session()
            ->forget([

                'active_role_id',

                'active_role_name',

            ]);

        $request
            ->session()
            ->invalidate();

        $request
            ->session()
            ->regenerateToken();

    }

}

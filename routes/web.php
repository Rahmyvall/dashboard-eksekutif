<?php

declare (strict_types = 1);

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Health Check
|--------------------------------------------------------------------------
*/

Route::get('/health', function (): JsonResponse {

    return response()->json([

        'status'    => 'success',

        'message'   => 'Laravel berjalan normal.',

        'timestamp' => now(),

    ]);

})
    ->name('health');

/*
|--------------------------------------------------------------------------
| Home
|--------------------------------------------------------------------------
*/

Route::get('/', function (): RedirectResponse {

    if (Auth::check()) {

        return redirect()
            ->route('dashboard');

    }

    return redirect()
        ->route('login');

})
    ->name('home');

/*
|--------------------------------------------------------------------------
| Guest
|--------------------------------------------------------------------------
*/

Route::middleware('guest')
    ->group(function () {

        Route::get(
            '/login',
            [
                LoginController::class,
                'create',
            ]
        )
            ->name('login');

        Route::post(
            '/login',
            [
                LoginController::class,
                'store',
            ]
        )
            ->name('login.process');

    });

/*
|--------------------------------------------------------------------------
| AUTHENTICATED
|--------------------------------------------------------------------------
*/

Route::middleware([

    'auth',

    'active.user',

])
    ->group(function () {

        /*
    |--------------------------------------------------------------------------
    | MAIN DASHBOARD REDIRECT
    |--------------------------------------------------------------------------
    */

        Route::get(
            '/dashboard',
            function (): RedirectResponse {

                $user = Auth::user();

                abort_if(

                    $user === null,

                    401

                );

                return match (true) {

                    $user->hasRole('super_admin')        =>

                    redirect()
                        ->route(
                            'super-admin.dashboard'
                        ),

                    $user->hasRole('direktur_utama')     =>

                    redirect()
                        ->route(
                            'direktur-utama.dashboard'
                        ),

                    $user->hasRole('hrd_manager')        =>

                    redirect()
                        ->route(
                            'hrd-manager.dashboard'
                        ),

                    $user->hasRole('manager_departemen') =>

                    redirect()
                        ->route(
                            'manager-departemen.dashboard'
                        ),

                    $user->hasRole('karyawan')           =>

                    redirect()
                        ->route(
                            'karyawan.dashboard'
                        ),

                    $user->hasRole('admin_pelayanan')    =>

                    redirect()
                        ->route(
                            'admin-pelayanan.dashboard'
                        ),

                    $user->hasRole('admin_operasional')  =>

                    redirect()
                        ->route(
                            'admin-operasional.dashboard'
                        ),

                    $user->hasRole('finance_staff')      =>

                    redirect()
                        ->route(
                            'finance-staff.dashboard'
                        ),

                    $user->hasRole('auditor_internal')   =>

                    redirect()
                        ->route(
                            'auditor-internal.dashboard'
                        ),

                    default                              =>

                    abort(
                        403,
                        'Role belum memiliki akses dashboard.'
                    )

                };

            }
        )
            ->name('dashboard');

        /*
    |--------------------------------------------------------------------------
    | SUPER ADMIN
    |--------------------------------------------------------------------------
    */

        Route::prefix('super-admin')
            ->name('super-admin.')
            ->middleware('role:super_admin')
            ->group(function () {

                Route::get(
                    '/dashboard',
                    [
                        DashboardController::class,
                        'index',
                    ]
                )
                    ->name('dashboard');

                /*
        User CRUD
        */

                Route::resource(

                    'users',

                    UserController::class

                );

                Route::get(

                    '/users-trash',

                    [

                        UserController::class,

                        'trash',

                    ]

                )
                    ->name('users.trash');

                Route::post(

                    '/users/{id}/restore',

                    [

                        UserController::class,

                        'restore',

                    ]

                )
                    ->whereNumber('id')

                    ->name('users.restore');

                Route::delete(

                    '/users/{id}/force-delete',

                    [

                        UserController::class,

                        'forceDelete',

                    ]

                )
                    ->whereNumber('id')

                    ->name('users.force-delete');

            });

        /*
    |--------------------------------------------------------------------------
    | DIREKTUR UTAMA
    |--------------------------------------------------------------------------
    */

        Route::prefix('direktur-utama')
            ->name('direktur-utama.')
            ->middleware('role:direktur_utama')
            ->group(function () {

                Route::get(

                    '/dashboard',

                    [

                        DashboardController::class,

                        'index',

                    ]

                )
                    ->name('dashboard');

            });

        /*
    |--------------------------------------------------------------------------
    | HRD MANAGER
    |--------------------------------------------------------------------------
    */

        Route::prefix('hrd-manager')
            ->name('hrd-manager.')
            ->middleware('role:hrd_manager')
            ->group(function () {

                Route::get(

                    '/dashboard',

                    [

                        DashboardController::class,

                        'index',

                    ]

                )
                    ->name('dashboard');

            });

        /*
    |--------------------------------------------------------------------------
    | MANAGER DEPARTEMEN
    |--------------------------------------------------------------------------
    */

        Route::prefix('manager-departemen')
            ->name('manager-departemen.')
            ->middleware('role:manager_departemen')
            ->group(function () {

                Route::get(

                    '/dashboard',

                    [

                        DashboardController::class,

                        'index',

                    ]

                )
                    ->name('dashboard');

            });

        /*
    |--------------------------------------------------------------------------
    | KARYAWAN
    |--------------------------------------------------------------------------
    */

        Route::prefix('karyawan')
            ->name('karyawan.')
            ->middleware('role:karyawan')
            ->group(function () {

                Route::get(

                    '/dashboard',

                    [

                        DashboardController::class,

                        'index',

                    ]

                )
                    ->name('dashboard');

            });

        /*
    |--------------------------------------------------------------------------
    | ADMIN PELAYANAN
    |--------------------------------------------------------------------------
    */

        Route::prefix('admin-pelayanan')
            ->name('admin-pelayanan.')
            ->middleware('role:admin_pelayanan')
            ->group(function () {

                Route::get(

                    '/dashboard',

                    [

                        DashboardController::class,

                        'index',

                    ]

                )
                    ->name('dashboard');

            });

        /*
    |--------------------------------------------------------------------------
    | ADMIN OPERASIONAL
    |--------------------------------------------------------------------------
    */

        Route::prefix('admin-operasional')
            ->name('admin-operasional.')
            ->middleware('role:admin_operasional')
            ->group(function () {

                Route::get(

                    '/dashboard',

                    [

                        DashboardController::class,

                        'index',

                    ]

                )
                    ->name('dashboard');

            });

        /*
    |--------------------------------------------------------------------------
    | FINANCE STAFF
    |--------------------------------------------------------------------------
    */

        Route::prefix('finance-staff')
            ->name('finance-staff.')
            ->middleware('role:finance_staff')
            ->group(function () {

                Route::get(

                    '/dashboard',

                    [

                        DashboardController::class,

                        'index',

                    ]

                )
                    ->name('dashboard');

            });

        /*
    |--------------------------------------------------------------------------
    | AUDITOR INTERNAL
    |--------------------------------------------------------------------------
    */

        Route::prefix('auditor-internal')
            ->name('auditor-internal.')
            ->middleware('role:auditor_internal')
            ->group(function () {

                Route::get(

                    '/dashboard',

                    [

                        DashboardController::class,

                        'index',

                    ]

                )
                    ->name('dashboard');

            });

        /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

        Route::post(

            '/logout',

            [

                LoginController::class,

                'destroy',

            ]

        )
            ->name('logout');

    });

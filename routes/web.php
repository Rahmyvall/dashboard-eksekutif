<?php

declare (strict_types = 1);

use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\PositionController;
use App\Http\Controllers\Admin\RoleController;
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
})->name('health');

/*
|--------------------------------------------------------------------------
| Home
|--------------------------------------------------------------------------
*/

Route::get('/', function (): RedirectResponse {
    return Auth::check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
})->name('home');

/*
|--------------------------------------------------------------------------
| Guest
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [LoginController::class, 'create'])
        ->name('login');

    Route::post('/login', [LoginController::class, 'store'])
        ->name('login.process');
});

/*
|--------------------------------------------------------------------------
| Authenticated Area
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'active.user'])->group(function (): void {
    /*
    |--------------------------------------------------------------------------
    | Main Dashboard Redirect
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', function (): RedirectResponse {
        $user = Auth::user();

        abort_if($user === null, 401, 'Anda harus login terlebih dahulu.');

        return match (true) {
            $user->hasRole('super_admin')        => redirect()->route('super-admin.dashboard'),
            $user->hasRole('direktur_utama')     => redirect()->route('direktur-utama.dashboard'),
            $user->hasRole('hrd_manager')        => redirect()->route('hrd-manager.dashboard'),
            $user->hasRole('manager_departemen') => redirect()->route('manager-departemen.dashboard'),
            $user->hasRole('karyawan')           => redirect()->route('karyawan.dashboard'),
            $user->hasRole('admin_pelayanan')    => redirect()->route('admin-pelayanan.dashboard'),
            $user->hasRole('admin_operasional')  => redirect()->route('admin-operasional.dashboard'),
            $user->hasRole('finance_staff')      => redirect()->route('finance-staff.dashboard'),
            $user->hasRole('auditor_internal')   => redirect()->route('auditor-internal.dashboard'),
            default                              => abort(403, 'Role belum memiliki akses dashboard.'),
        };
    })->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Shared Branch Management
    |--------------------------------------------------------------------------
    |
    | Lihat:
    | - super_admin
    | - direktur_utama
    | - admin_operasional
    | - auditor_internal
    |
    | Kelola:
    | - super_admin
    | - admin_operasional
    |
    | Sampah:
    | - super_admin
    |
    */

    Route::prefix('branches')
        ->name('branches.')
        ->group(function (): void {
            /*
            |------------------------------------------------------------------
            | Index — empat role
            |------------------------------------------------------------------
            */

            Route::middleware(
                'role:super_admin|direktur_utama|admin_operasional|auditor_internal'
            )->group(function (): void {
                Route::get('/', [BranchController::class, 'index'])
                    ->name('index');
            });

            /*
            |------------------------------------------------------------------
            | Create dan Store — Super Admin + Admin Operasional
            |------------------------------------------------------------------
            */

            Route::middleware(
                'role:super_admin|admin_operasional'
            )->group(function (): void {
                Route::get('/create', [BranchController::class, 'create'])
                    ->name('create');

                Route::post('/', [BranchController::class, 'store'])
                    ->name('store');
            });

            /*
            |------------------------------------------------------------------
            | Recycle Bin — Super Admin
            |------------------------------------------------------------------
            |
            | Route statis diletakkan sebelum /{branch} agar kata "trash"
            | tidak dianggap sebagai parameter model Branch.
            */

            Route::middleware('role:super_admin')->group(function (): void {
                Route::get('/trash', [BranchController::class, 'trash'])
                    ->name('trash');

                Route::post('/{id}/restore', [BranchController::class, 'restore'])
                    ->whereNumber('id')
                    ->name('restore');

                Route::delete('/{id}/force-delete', [BranchController::class, 'forceDelete'])
                    ->whereNumber('id')
                    ->name('force-delete');
            });

            /*
            |------------------------------------------------------------------
            | Approval dan Penolakan — Role sesuai tahap persetujuan
            |------------------------------------------------------------------
            |
            | Direktur Utama memproses tahap pertama.
            | Auditor Internal memproses tahap terakhir.
            | Super Admin dapat memproses tahap approval yang sedang aktif.
            |
            | Route ini harus berada sebelum route /{branch} agar struktur
            | URL tetap jelas dan mudah dipelihara.
            */

            Route::middleware(
                'role:super_admin|direktur_utama|auditor_internal'
            )->group(function (): void {
                Route::patch('/{branch}/approve', [BranchController::class, 'approve'])
                    ->whereNumber('branch')
                    ->name('approve');

                Route::patch('/{branch}/reject', [BranchController::class, 'reject'])
                    ->whereNumber('branch')
                    ->name('reject');
            });

            /*
            |------------------------------------------------------------------
            | Edit, Update, Delete — Super Admin + Admin Operasional
            |------------------------------------------------------------------
            */

            Route::middleware(
                'role:super_admin|admin_operasional'
            )->group(function (): void {
                Route::get('/{branch}/edit', [BranchController::class, 'edit'])
                    ->whereNumber('branch')
                    ->name('edit');

                Route::match(['put', 'patch'], '/{branch}', [BranchController::class, 'update'])
                    ->whereNumber('branch')
                    ->name('update');

                Route::delete('/{branch}', [BranchController::class, 'destroy'])
                    ->whereNumber('branch')
                    ->name('destroy');
            });

            /*
            |------------------------------------------------------------------
            | Show — empat role
            |------------------------------------------------------------------
            |
            | Harus berada paling bawah agar tidak menangkap URL create/trash.
            */

            Route::middleware(
                'role:super_admin|direktur_utama|admin_operasional|auditor_internal'
            )->group(function (): void {
                Route::get('/{branch}', [BranchController::class, 'show'])
                    ->whereNumber('branch')
                    ->name('show');
            });
        });

    /*
    |--------------------------------------------------------------------------
    | Shared Department Management
    |--------------------------------------------------------------------------
    |
    | Index dan Show:
    | - super_admin
    | - direktur_utama
    | - hrd_manager
    | - manager_departemen
    | - auditor_internal
    |
    | Pengelolaan penuh:
    | - super_admin
    |
    */

    Route::prefix('super-admin')
        ->name('super-admin.')
        ->group(function (): void {
            Route::prefix('departments')
                ->name('departments.')
                ->controller(DepartmentController::class)
                ->group(function (): void {
                    /*
                    |----------------------------------------------------------
                    | Create dan Store — khusus Super Admin
                    |----------------------------------------------------------
                    */

                    Route::middleware('role:super_admin')
                        ->group(function (): void {
                            Route::get('/create', 'create')
                                ->name('create');

                            Route::post('/', 'store')
                                ->name('store');
                        });

                    /*
                    |----------------------------------------------------------
                    | Edit, Update, dan Delete — khusus Super Admin
                    |----------------------------------------------------------
                    */

                    Route::middleware('role:super_admin')
                        ->group(function (): void {
                            Route::get('/{department}/edit', 'edit')
                                ->whereNumber('department')
                                ->name('edit');

                            Route::match(
                                ['put', 'patch'],
                                '/{department}',
                                'update'
                            )
                                ->whereNumber('department')
                                ->name('update');

                            Route::delete('/{department}', 'destroy')
                                ->whereNumber('department')
                                ->name('destroy');
                        });

                    /*
                    |----------------------------------------------------------
                    | Index dan Show — lima role
                    |----------------------------------------------------------
                    */

                    Route::middleware(
                        'role:super_admin|direktur_utama|hrd_manager|manager_departemen|auditor_internal'
                    )->group(function (): void {
                        Route::get('/', 'index')
                            ->name('index');

                        Route::get('/{department}', 'show')
                            ->whereNumber('department')
                            ->name('show');
                    });
                });

            /*
            |--------------------------------------------------------------
            | Recycle Bin Department — khusus Super Admin
            |--------------------------------------------------------------
            |
            | URL dan nama route lama dipertahankan agar Blade tetap bekerja.
            |
            */

            Route::middleware('role:super_admin')
                ->group(function (): void {
                    Route::get(
                        '/departments-trash',
                        [DepartmentController::class, 'trash']
                    )
                        ->name('departments.trash');

                    Route::post(
                        '/departments/{id}/restore',
                        [DepartmentController::class, 'restore']
                    )
                        ->whereNumber('id')
                        ->name('departments.restore');

                    Route::delete(
                        '/departments/{id}/force-delete',
                        [DepartmentController::class, 'forceDelete']
                    )
                        ->whereNumber('id')
                        ->name('departments.force-delete');
                });
        });

    /*
    |--------------------------------------------------------------------------
    | Shared Position Management
    |--------------------------------------------------------------------------
    |
    | Index dan Show:
    | - super_admin
    | - direktur_utama
    | - hrd_manager
    | - manager_departemen
    | - auditor_internal
    |
    | Pengelolaan penuh:
    | - super_admin
    |
    */

    Route::prefix('super-admin')
        ->name('super-admin.')
        ->group(function (): void {
            Route::prefix('positions')
                ->name('positions.')
                ->controller(PositionController::class)
                ->group(function (): void {
                    /*
                    |----------------------------------------------------------
                    | Create dan Store — khusus Super Admin
                    |----------------------------------------------------------
                    */

                    Route::middleware('role:super_admin')
                        ->group(function (): void {
                            Route::get('/create', 'create')
                                ->name('create');

                            Route::post('/', 'store')
                                ->name('store');
                        });

                    /*
                    |----------------------------------------------------------
                    | Edit, Update, dan Delete — khusus Super Admin
                    |----------------------------------------------------------
                    */

                    Route::middleware('role:super_admin')
                        ->group(function (): void {
                            Route::get('/{position}/edit', 'edit')
                                ->whereNumber('position')
                                ->name('edit');

                            Route::match(
                                ['put', 'patch'],
                                '/{position}',
                                'update'
                            )
                                ->whereNumber('position')
                                ->name('update');

                            Route::delete('/{position}', 'destroy')
                                ->whereNumber('position')
                                ->name('destroy');
                        });

                    /*
                    |----------------------------------------------------------
                    | Index dan Show — lima role
                    |----------------------------------------------------------
                    */

                    Route::middleware(
                        'role:super_admin|direktur_utama|hrd_manager|manager_departemen|auditor_internal'
                    )->group(function (): void {
                        Route::get('/', 'index')
                            ->name('index');

                        /*
                         * Route show diletakkan paling bawah agar URL create
                         * tidak dianggap sebagai parameter {position}.
                         */
                        Route::get('/{position}', 'show')
                            ->whereNumber('position')
                            ->name('show');
                    });
                });

            /*
            |--------------------------------------------------------------
            | Recycle Bin Position — khusus Super Admin
            |--------------------------------------------------------------
            */

            Route::middleware('role:super_admin')
                ->group(function (): void {
                    Route::get(
                        '/positions-trash',
                        [PositionController::class, 'trash']
                    )
                        ->name('positions.trash');

                    Route::post(
                        '/positions/{id}/restore',
                        [PositionController::class, 'restore']
                    )
                        ->whereNumber('id')
                        ->name('positions.restore');

                    Route::delete(
                        '/positions/{id}/force-delete',
                        [PositionController::class, 'forceDelete']
                    )
                        ->whereNumber('id')
                        ->name('positions.force-delete');
                });
        });

    /*
    |--------------------------------------------------------------------------
    | Super Admin
    |--------------------------------------------------------------------------
    */

    Route::prefix('super-admin')
        ->name('super-admin.')
        ->middleware('role:super_admin')
        ->group(function (): void {

            Route::get('/dashboard', [DashboardController::class, 'index'])
                ->defaults('dashboard_role', 'super_admin')
                ->name('dashboard');

            /*
        |--------------------------------------------------------------------------
        | User Management
        |--------------------------------------------------------------------------
        */

            Route::resource('users', UserController::class);

            Route::resource('roles', RoleController::class);

            Route::get('/users-trash', [UserController::class, 'trash'])
                ->name('users.trash');

            Route::post('/users/{id}/restore', [UserController::class, 'restore'])
                ->whereNumber('id')
                ->name('users.restore');

            Route::delete('/users/{id}/force-delete', [UserController::class, 'forceDelete'])
                ->whereNumber('id')
                ->name('users.force-delete');

        });

    /*
    |--------------------------------------------------------------------------
    | Direktur Utama
    |--------------------------------------------------------------------------
    */

    Route::prefix('direktur-utama')
        ->name('direktur-utama.')
        ->middleware('role:direktur_utama')
        ->group(function (): void {
            Route::get('/dashboard', [DashboardController::class, 'index'])
                ->defaults('dashboard_role', 'direktur_utama')
                ->name('dashboard');
        });

    /*
    |--------------------------------------------------------------------------
    | HRD Manager
    |--------------------------------------------------------------------------
    */

    Route::prefix('hrd-manager')
        ->name('hrd-manager.')
        ->middleware('role:hrd_manager')
        ->group(function (): void {
            Route::get('/dashboard', [DashboardController::class, 'index'])
                ->defaults('dashboard_role', 'hrd_manager')
                ->name('dashboard');
        });

    /*
    |--------------------------------------------------------------------------
    | Manager Departemen
    |--------------------------------------------------------------------------
    */

    Route::prefix('manager-departemen')
        ->name('manager-departemen.')
        ->middleware('role:manager_departemen')
        ->group(function (): void {
            Route::get('/dashboard', [DashboardController::class, 'index'])
                ->defaults('dashboard_role', 'manager_departemen')
                ->name('dashboard');
        });

    /*
    |--------------------------------------------------------------------------
    | Karyawan
    |--------------------------------------------------------------------------
    */

    Route::prefix('karyawan')
        ->name('karyawan.')
        ->middleware('role:karyawan')
        ->group(function (): void {
            Route::get('/dashboard', [DashboardController::class, 'index'])
                ->defaults('dashboard_role', 'karyawan')
                ->name('dashboard');
        });

    /*
    |--------------------------------------------------------------------------
    | Admin Pelayanan
    |--------------------------------------------------------------------------
    */

    Route::prefix('admin-pelayanan')
        ->name('admin-pelayanan.')
        ->middleware('role:admin_pelayanan')
        ->group(function (): void {
            Route::get('/dashboard', [DashboardController::class, 'index'])
                ->defaults('dashboard_role', 'admin_pelayanan')
                ->name('dashboard');
        });

    /*
    |--------------------------------------------------------------------------
    | Admin Operasional
    |--------------------------------------------------------------------------
    */

    Route::prefix('admin-operasional')
        ->name('admin-operasional.')
        ->middleware('role:admin_operasional')
        ->group(function (): void {
            Route::get('/dashboard', [DashboardController::class, 'index'])
                ->defaults('dashboard_role', 'admin_operasional')
                ->name('dashboard');
        });

    /*
    |--------------------------------------------------------------------------
    | Finance Staff
    |--------------------------------------------------------------------------
    */

    Route::prefix('finance-staff')
        ->name('finance-staff.')
        ->middleware('role:finance_staff')
        ->group(function (): void {
            Route::get('/dashboard', [DashboardController::class, 'index'])
                ->defaults('dashboard_role', 'finance_staff')
                ->name('dashboard');
        });

    /*
    |--------------------------------------------------------------------------
    | Auditor Internal
    |--------------------------------------------------------------------------
    */

    Route::prefix('auditor-internal')
        ->name('auditor-internal.')
        ->middleware('role:auditor_internal')
        ->group(function (): void {
            Route::get('/dashboard', [DashboardController::class, 'index'])
                ->defaults('dashboard_role', 'auditor_internal')
                ->name('dashboard');
        });

    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */

    Route::post('/logout', [LoginController::class, 'destroy'])
        ->name('logout');
});

<?php

declare (strict_types = 1);

use App\Http\Controllers\Admin\UserController as AdminUserController;
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

Route::get(
    '/health',
    static function (): JsonResponse {
        return response()->json([
            'status'      => 'success',
            'message'     => 'Laravel berjalan dengan normal.',
            'application' => config('app.name'),
            'environment' => app()->environment(),
            'timestamp'   => now()->toIso8601String(),
        ]);
    }
)->name('health');

/*
|--------------------------------------------------------------------------
| Halaman Utama
|--------------------------------------------------------------------------
*/

Route::get(
    '/',
    static function (): RedirectResponse {
        if (Auth::guard('web')->check()) {
            return redirect()->route('dashboard');
        }

        return redirect()->route('login');
    }
)->name('home');

/*
|--------------------------------------------------------------------------
| Authentication untuk Guest
|--------------------------------------------------------------------------
|
| Route ini hanya dapat diakses pengguna yang belum login.
|
*/

Route::middleware('guest')
    ->group(function (): void {
        /*
        |--------------------------------------------------------------------------
        | Halaman Login
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/login',
            [LoginController::class, 'create']
        )->name('login');

        /*
        |--------------------------------------------------------------------------
        | Proses Login
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/login',
            [LoginController::class, 'store']
        )->name('login.process');
    });

/*
|--------------------------------------------------------------------------
| Route Pengguna Terautentikasi
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'active.user',
])->group(function (): void {
    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    |
    | Semua role tetap memakai satu route /dashboard.
    | DashboardController akan menentukan view berdasarkan active_role_name
    | yang tersimpan dalam session.
    |
    */

    Route::get(
        '/dashboard',
        [DashboardController::class, 'index']
    )->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Manajemen Pengguna
    |--------------------------------------------------------------------------
    |
    | Hanya Super Admin dan HRD yang dapat mengakses manajemen pengguna.
    |
    */

    Route::prefix('admin')
        ->name('admin.')
        ->middleware('role:SUPER_ADMIN,HRD')
        ->group(function (): void {
            /*
            |--------------------------------------------------------------------------
            | Recycle Bin
            |--------------------------------------------------------------------------
            |
            | Route statis ini wajib ditempatkan sebelum Route::resource().
            | Jika diletakkan setelah resource, "users-trash" berpotensi dianggap
            | sebagai nilai parameter {user}.
            |
            */

            Route::get(
                '/users-trash',
                [AdminUserController::class, 'trash']
            )->name('users.trash');

            /*
            |--------------------------------------------------------------------------
            | Restore User
            |--------------------------------------------------------------------------
            */

            Route::post(
                '/users/{id}/restore',
                [AdminUserController::class, 'restore']
            )
                ->whereNumber('id')
                ->name('users.restore');

            /*
            |--------------------------------------------------------------------------
            | Hapus Permanen
            |--------------------------------------------------------------------------
            |
            | Hanya SUPER_ADMIN yang boleh menghapus user secara permanen.
            |
            */

            Route::delete(
                '/users/{id}/force-delete',
                [AdminUserController::class, 'forceDelete']
            )
                ->whereNumber('id')
                ->middleware('role:SUPER_ADMIN')
                ->name('users.forceDelete');

            /*
            |--------------------------------------------------------------------------
            | User CRUD
            |--------------------------------------------------------------------------
            |
            | Menghasilkan route:
            |
            | admin.users.index
            | admin.users.create
            | admin.users.store
            | admin.users.show
            | admin.users.edit
            | admin.users.update
            | admin.users.destroy
            |
            */

            Route::resource(
                'users',
                AdminUserController::class
            );
        });

    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/logout',
        [LoginController::class, 'destroy']
    )->name('logout');
});

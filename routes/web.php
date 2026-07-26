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

Route::get('/health', static function (): JsonResponse {
    return response()->json([
        'status'      => 'success',
        'message'     => 'Laravel berjalan dengan normal.',
        'application' => config('app.name'),
        'environment' => app()->environment(),
        'timestamp'   => now()->toIso8601String(),
    ]);
})->name('health');

/*
|--------------------------------------------------------------------------
| Home
|--------------------------------------------------------------------------
*/

Route::get('/', static function (): RedirectResponse {

    if (Auth::guard('web')->check()) {
        return redirect()->route('dashboard');
    }

    return redirect()->route('login');

})->name('home');

/*
|--------------------------------------------------------------------------
| Authentication Guest
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function (): void {

    Route::get(
        '/login',
        [LoginController::class, 'create']
    )->name('login');

    Route::post(
        '/login',
        [LoginController::class, 'store']
    )->name('login.process');

});

/*
|--------------------------------------------------------------------------
| Authenticated User
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
    */

    Route::get(
        '/dashboard',
        [DashboardController::class, 'index']
    )->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Admin User Management
    |--------------------------------------------------------------------------
    |
    | Hanya super_admin yang boleh mengelola user.
    |
    */

    Route::prefix('admin')
        ->name('admin.')
        ->middleware('role:super_admin')
        ->group(function (): void {

            /*
            |--------------------------------------------------------------------------
            | Trash User
            |--------------------------------------------------------------------------
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
            | Permanent Delete
            |--------------------------------------------------------------------------
            */

            Route::delete(
                '/users/{id}/force-delete',
                [AdminUserController::class, 'forceDelete']
            )
                ->whereNumber('id')
                ->middleware('role:super_admin')
                ->name('users.forceDelete');

            /*
            |--------------------------------------------------------------------------
            | CRUD Users
            |--------------------------------------------------------------------------
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

<?php

declare (strict_types = 1);

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

Route::get('/', function (): RedirectResponse {

    return Auth::check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');

})->name('home');

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function (): void {

    /*
    |--------------------------------------------------------------------------
    | Login Page
    |--------------------------------------------------------------------------
    */

    Route::get('/login', [
        LoginController::class,
        'showLoginForm',
    ])->name('login');

    /*
    |--------------------------------------------------------------------------
    | Login Process
    |--------------------------------------------------------------------------
    */

    Route::post('/login', [
        LoginController::class,
        'login',
    ])->name('login.process');

});

/*
|--------------------------------------------------------------------------
| Protected Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function (): void {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', [
        DashboardController::class,
        'index',
    ])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */

    Route::post('/logout', [
        LoginController::class,
        'logout',
    ])->name('logout');

});
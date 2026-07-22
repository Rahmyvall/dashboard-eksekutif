<?php

declare (strict_types = 1);

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Domain utama
|--------------------------------------------------------------------------
|
| Jika sudah login, menuju dashboard.
| Jika belum login, menuju halaman login.
|
*/

Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
})->name('home');

/*
|--------------------------------------------------------------------------
| Login
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [
        LoginController::class,
        'showLoginForm',
    ])->name('login');

    Route::post('/login', [
        LoginController::class,
        'login',
    ])->name('login.process');
});

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function (): void {
    Route::get('/dashboard', [
        DashboardController::class,
        'index',
    ])->name('dashboard');

    Route::post('/logout', [
        LoginController::class,
        'logout',
    ])->name('logout');
});
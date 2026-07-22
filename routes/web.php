<?php

declare (strict_types = 1);

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Halaman login
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
|
| Dashboard menggunakan alamat utama "/".
| Pengguna yang belum login otomatis diarahkan ke route "login".
|
*/

Route::middleware('auth')->group(function (): void {
    Route::get('/', [
        DashboardController::class,
        'index',
    ])->name('dashboard');

    Route::post('/logout', [
        LoginController::class,
        'logout',
    ])->name('logout');
});
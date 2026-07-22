<?php

declare (strict_types = 1);

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Health Check
|--------------------------------------------------------------------------
*/

Route::get('/health', function () {
    return response()->json([
        'status'  => 'ok',
        'message' => 'Laravel berjalan',
    ]);
})->name('health');

/*
|--------------------------------------------------------------------------
| Landing Page
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

Route::get('/login', [
    LoginController::class,
    'showLoginForm',
])->name('login');

Route::post('/login', [
    LoginController::class,
    'login',
])->name('login.process');

Route::post('/logout', [
    LoginController::class,
    'logout',
])->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', [
    DashboardController::class,
    'index',
])
    ->middleware('auth')
    ->name('dashboard');
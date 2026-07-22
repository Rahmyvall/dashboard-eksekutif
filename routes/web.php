<?php

declare (strict_types = 1);

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

/*
|--------------------------------------------------------------------------
| Tes server
|--------------------------------------------------------------------------
*/

Route::get('/test-server', function () {
    return response('SERVER LARAVEL BERHASIL', 200);
});

/*
|--------------------------------------------------------------------------
| Login sementara tanpa LoginController
|--------------------------------------------------------------------------
*/

Route::get('/login', function () {
    return view('welcome');
})->name('login');

Route::post('/login', [
    LoginController::class,
    'login',
])->name('login.process');

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
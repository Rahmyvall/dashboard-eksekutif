<?php

declare (strict_types = 1);

use App\Http\Middleware\EnsureUserIsActive;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(
    basePath: dirname(__DIR__)
)

/*
    |--------------------------------------------------------------------------
    | Routing
    |--------------------------------------------------------------------------
    */

    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )

/*
    |--------------------------------------------------------------------------
    | Middleware
    |--------------------------------------------------------------------------
    */

    ->withMiddleware(function (Middleware $middleware): void {
        /*
        |--------------------------------------------------------------------------
        | Custom Middleware Alias
        |--------------------------------------------------------------------------
        */

        $middleware->alias([
            /*
            |--------------------------------------------------------------------------
            | Check User Active
            |--------------------------------------------------------------------------
            */

            'active.user' => EnsureUserIsActive::class,

            /*
            |--------------------------------------------------------------------------
            | Role Middleware
            |--------------------------------------------------------------------------
            |
            | Contoh:
            |
            | ->middleware('role:super_admin')
            |
            */

            'role'        => RoleMiddleware::class,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Authentication Redirect
        |--------------------------------------------------------------------------
        |
        | Redirect ini hanya digunakan untuk request web. Request API akan
        | dirender sebagai JSON melalui konfigurasi exceptions di bawah.
        |
        */

        $middleware->redirectGuestsTo(
            fn(Request $request): string => route('login')
        );

        /*
        |--------------------------------------------------------------------------
        | Already Authenticated Redirect
        |--------------------------------------------------------------------------
        */

        $middleware->redirectUsersTo(
            fn(Request $request): string => route('dashboard')
        );
    })

/*
    |--------------------------------------------------------------------------
    | Exceptions
    |--------------------------------------------------------------------------
    */

    ->withExceptions(function (Exceptions $exceptions): void {
        /*
        |--------------------------------------------------------------------------
        | Force API Exceptions to JSON
        |--------------------------------------------------------------------------
        |
        | Semua request menuju /api/* akan menghasilkan respons JSON,
        | termasuk error validasi, route model binding 404, autentikasi 401,
        | authorization 403, dan server error 500.
        |
        | Dengan konfigurasi ini, Postman tidak lagi menerima halaman HTML
        | dashboard ketika terjadi exception pada endpoint API.
        |
        */

        $exceptions->shouldRenderJsonWhen(
            function (
                Request $request,
                \Throwable $exception
            ): bool {
                return $request->is('api/*')
                || $request->expectsJson();
            }
        );

        /*
        |--------------------------------------------------------------------------
        | Sensitive Input
        |--------------------------------------------------------------------------
        |
        | Nilai berikut tidak akan dimasukkan ke flash session ketika terjadi
        | validation exception pada request web.
        |
        */

        $exceptions->dontFlash([
            'current_password',
            'password',
            'password_confirmation',
        ]);
    })

    ->create();

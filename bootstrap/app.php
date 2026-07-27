<?php

declare (strict_types = 1);

use App\Http\Middleware\EnsureUserIsActive;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(
    basePath: dirname(__DIR__)
)

    ->withRouting(

        web: __DIR__ . '/../routes/web.php',

        commands: __DIR__ . '/../routes/console.php',

        health: '/up',

    )

    ->withMiddleware(function (Middleware $middleware): void {

        /*
    |--------------------------------------------------------------------------
    | Middleware Alias
    |--------------------------------------------------------------------------
    |
    | Daftar middleware custom Laravel 13.
    |
    */

        $middleware->alias([

            /*
        | Cek user aktif
        */
            'active.user' => EnsureUserIsActive::class,

            /*
        | Role permission
        |
        | Contoh:
        | ->middleware('role:ADMIN')
        | ->middleware('role:SUPER_ADMIN,ADMIN')
        |
        */
            'role'        => RoleMiddleware::class,

        ]);

        /*
    |--------------------------------------------------------------------------
    | Redirect Authentication
    |--------------------------------------------------------------------------
    |
    | Jika user belum login diarahkan ke login.
    |
    */

        $middleware->redirectGuestsTo(

            fn() => route('login')

        );

        /*
    |--------------------------------------------------------------------------
    | Redirect User Setelah Login
    |--------------------------------------------------------------------------
    |
    | Jika user sudah login dan membuka halaman guest.
    |
    */

        $middleware->redirectUsersTo(

            fn() => route('dashboard')

        );

    })

    ->withExceptions(function (Exceptions $exceptions): void {

        //

    })

    ->create();

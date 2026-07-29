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

/*
|--------------------------------------------------------------------------
| ROUTING
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
| MIDDLEWARE
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
        | Example:
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
    */

        $middleware->redirectGuestsTo(

            fn() => route('login')

        );

        /*
    |--------------------------------------------------------------------------
    | Already Login Redirect
    |--------------------------------------------------------------------------
    */

        $middleware->redirectUsersTo(

            fn() => route('dashboard')

        );

    })

/*
|--------------------------------------------------------------------------
| EXCEPTIONS
|--------------------------------------------------------------------------
*/

    ->withExceptions(function (Exceptions $exceptions): void {

        //

    })

    ->create();

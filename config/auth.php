<?php

declare (strict_types = 1);

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | Default guard dan password broker yang digunakan aplikasi.
    |
    */

    'defaults'         => [

        'guard'     => env(
            'AUTH_GUARD',
            'web'
        ),

        'passwords' => env(
            'AUTH_PASSWORD_BROKER',
            'users'
        ),

    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Guard web menggunakan session authentication.
    |
    */

    'guards'           => [

        'web' => [

            'driver'   => 'session',

            'provider' => 'users',

        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | User diambil menggunakan Eloquent Model.
    |
    */

    'providers'        => [

        'users' => [

            'driver' => 'eloquent',

            'model'  => App\Models\User::class,

        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Password Resetting
    |--------------------------------------------------------------------------
    |
    | Konfigurasi fitur reset password.
    |
    */

    'passwords'        => [

        'users' => [

            'provider' => 'users',

            'table'    => env(
                'AUTH_PASSWORD_RESET_TOKEN_TABLE',
                'password_reset_tokens'
            ),

            'expire'   => env(
                'AUTH_PASSWORD_RESET_TOKEN_EXPIRE',
                60
            ),

            'throttle' => env(
                'AUTH_PASSWORD_RESET_TOKEN_THROTTLE',
                60
            ),

        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | Lama waktu sebelum password harus dikonfirmasi ulang.
    |
    */

    'password_timeout' => env(
        'AUTH_PASSWORD_TIMEOUT',
        10800
    ),

];
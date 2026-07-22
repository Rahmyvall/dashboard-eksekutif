<?php

declare (strict_types = 1);

use App\Models\User;

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | Guard web digunakan untuk autentikasi berbasis session.
    | Broker users digunakan untuk fitur reset password.
    |
    */

    'defaults'         => [
        'guard'     => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Guard web menggunakan session dan mengambil pengguna melalui
    | provider users.
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
    | Provider users mengambil data pengguna melalui model Eloquent
    | App\Models\User.
    |
    */

    'providers'        => [
        'users' => [
            'driver' => 'eloquent',
            'model'  => User::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | Konfigurasi token reset password pengguna.
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
    | Masa berlaku konfirmasi password dalam detik.
    | Nilai default 10800 detik atau 3 jam.
    |
    */

    'password_timeout' => env(
        'AUTH_PASSWORD_TIMEOUT',
        10800
    ),

];

<?php

declare (strict_types = 1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Tampilkan halaman login
     */
    public function showLoginForm(): View
    {
        return view('welcome');
    }

    /**
     * Proses login
     */
    public function login(Request $request): RedirectResponse
    {
        $validated = $request->validate(
            [
                'email'    => [
                    'required',
                    'email',
                    'max:150',
                ],

                'password' => [
                    'required',
                    'string',
                    'max:255',
                ],
            ],
            [
                'email.required'    =>
                'Email wajib diisi.',

                'email.email'       =>
                'Format email tidak valid.',

                'password.required' =>
                'Password wajib diisi.',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Normalisasi Email
        |--------------------------------------------------------------------------
        */

        $credentials = [
            'email'    => Str::lower(
                trim($validated['email'])
            ),

            'password' =>
            $validated['password'],
        ];

        /*
        |--------------------------------------------------------------------------
        | Authentication Laravel 13
        |--------------------------------------------------------------------------
        */

        if (
            ! Auth::attempt(
                $credentials,
                $request->boolean('remember')
            )
        ) {

            Log::warning(
                'Login gagal',
                [
                    'email' =>
                    $credentials['email'],

                    'ip'    =>
                    $request->ip(),

                    'time'  =>
                    now(),
                ]
            );

            return back()
                ->withErrors([
                    'email' =>
                    'Email atau password salah.',
                ])
                ->onlyInput('email');
        }

        /*
        |--------------------------------------------------------------------------
        | Regenerate Session
        |--------------------------------------------------------------------------
        */

        $request
            ->session()
            ->regenerate();

        /*
        |--------------------------------------------------------------------------
        | Redirect Setelah Login
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->intended(
                route('dashboard')
            )
            ->with(
                'success',
                'Selamat datang kembali.'
            );
    }

    /**
     * Logout
     */
    public function logout(Request $request): RedirectResponse
    {

        Auth::logout();

        $request
            ->session()
            ->invalidate();

        $request
            ->session()
            ->regenerateToken();

        return redirect()
            ->route('login')
            ->with(
                'success',
                'Anda telah logout.'
            );
    }
}
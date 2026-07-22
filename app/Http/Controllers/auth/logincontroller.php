<?php

declare (strict_types = 1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Menampilkan halaman login.
     */
    public function showLoginForm(): View
    {
        return view('welcome');
    }

    /**
     * Memproses autentikasi pengguna.
     */
    public function login(Request $request): RedirectResponse
    {
        /*
         * Normalisasi email sebelum validasi.
         */
        $request->merge([
            'email' => Str::lower(
                trim((string) $request->input('email'))
            ),
        ]);

        /*
         * Validasi data login.
         */
        $validated = $request->validate(
            [
                'email'    => [
                    'required',
                    'string',
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
                'email.required'    => 'Email wajib diisi.',
                'email.string'      => 'Email harus berupa teks.',
                'email.email'       => 'Format email tidak valid.',
                'email.max'         => 'Email maksimal 150 karakter.',

                'password.required' => 'Password wajib diisi.',
                'password.string'   => 'Password harus berupa teks.',
                'password.max'      => 'Password maksimal 255 karakter.',
            ]
        );

        $credentials = [
            'email'    => $validated['email'],
            'password' => $validated['password'],
        ];

        $guard = Auth::guard('web');

        /*
         * Proses autentikasi pengguna.
         */
        if (
            ! $guard->attempt(
                $credentials,
                $request->boolean('remember')
            )
        ) {
            return back()
                ->withErrors([
                    'email' => 'Email atau password yang dimasukkan tidak sesuai.',
                ])
                ->onlyInput('email');
        }

        /*
         * Membuat session ID baru setelah login berhasil.
         */
        $request->session()->regenerate();

        $user = $guard->user();

        $userName = $user?->name ?? $user?->email ?? 'Pengguna';

        /*
         * Login berhasil dan selalu diarahkan ke dashboard.
         */
        return redirect()
            ->route('dashboard')
            ->with(
                'success',
                'Login berhasil. Selamat datang, '
                . $userName
                . '.'
            );
    }

    /**
     * Memproses logout pengguna.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        /*
         * Menghapus session lama dan membuat token CSRF baru.
         */
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with(
                'success',
                'Anda berhasil logout.'
            );
    }
}
<?php

declare (strict_types = 1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use RuntimeException;

class LoginController extends Controller
{
    /**
     * Maksimal percobaan login gagal.
     */
    private const MAX_LOGIN_ATTEMPTS = 5;

    /**
     * Durasi pembatasan login dalam detik.
     */
    private const LOGIN_DECAY_SECONDS = 60;

    /**
     * Menampilkan halaman login.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('login');
    }

    /**
     * Memproses autentikasi pengguna.
     *
     * @throws ValidationException
     */
    public function login(Request $request): RedirectResponse
    {
        /*
         * Normalisasi email dan nilai checkbox remember.
         */
        $request->merge([
            'email'    => Str::lower(
                trim((string) $request->input('email'))
            ),
            'remember' => $request->boolean('remember'),
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
                'remember' => [
                    'boolean',
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

                'remember.boolean'  => 'Pilihan ingat saya tidak valid.',
            ]
        );

        /*
         * Membuat kunci rate limiter berdasarkan email dan IP.
         */
        $throttleKey = $this->throttleKey(
            $validated['email'],
            (string) $request->ip()
        );

        $this->ensureIsNotRateLimited($throttleKey);

        /*
         * Mencari pengguna berdasarkan email.
         */
        $user = User::query()
            ->with('role')
            ->whereRaw(
                'LOWER(TRIM(email)) = ?',
                [$validated['email']]
            )
            ->first();

        /*
         * Email tidak ditemukan.
         */
        if (! $user instanceof User) {
            $this->failCredentials(
                $throttleKey,
                $this->developmentMessage(
                    'Email tidak terdaftar.'
                )
            );
        }

        /*
         * Memeriksa password.
         */
        try {
            $passwordMatches = Hash::check(
                $validated['password'],
                (string) $user->password
            );
        } catch (RuntimeException $exception) {
            report($exception);

            $this->failCredentials(
                $throttleKey,
                $this->developmentMessage(
                    'Format hash password di database tidak valid.'
                )
            );
        }

        /*
         * Password salah.
         */
        if (! $passwordMatches) {
            $this->failCredentials(
                $throttleKey,
                $this->developmentMessage(
                    'Password yang dimasukkan salah.'
                )
            );
        }

        /*
         * Memastikan akun aktif.
         */
        if (! $user->isActive()) {
            $this->denyAccess(
                $throttleKey,
                'Akun tidak aktif. Silakan hubungi administrator.'
            );
        }

        /*
         * Memastikan akun memiliki role.
         */
        if ($user->role === null) {
            $this->denyAccess(
                $throttleKey,
                'Akun belum memiliki role akses.'
            );
        }

        /*
         * Memastikan role menggunakan guard web.
         */
        if ((string) $user->role->guard_name !== 'web') {
            $this->denyAccess(
                $throttleKey,
                'Guard role akun tidak sesuai.'
            );
        }

        /*
         * Memastikan role dapat mengakses dashboard eksekutif.
         */
        if (! $user->canAccessExecutiveDashboard()) {
            $roleName = (string) $user->role->name;

            $this->denyAccess(
                $throttleKey,
                'Role '
                . $roleName
                . ' tidak memiliki akses ke Dashboard Eksekutif.'
            );
        }

        /*
         * Memperbarui hash password jika konfigurasi hashing berubah.
         */
        if (Hash::needsRehash((string) $user->password)) {
            $user->forceFill([
                'password' => Hash::make(
                    $validated['password']
                ),
            ])->save();
        }

        /*
         * Login menggunakan guard web.
         */
        Auth::guard('web')->login(
            $user,
            (bool) ($validated['remember'] ?? false)
        );

        /*
         * Regenerasi session setelah autentikasi berhasil.
         */
        $request->session()->regenerate();

        /*
         * Menghapus riwayat percobaan login gagal.
         */
        RateLimiter::clear($throttleKey);

        /*
         * Menyimpan waktu login terakhir.
         *
         * Pastikan tabel users memiliki kolom last_login_at.
         */
        $user->forceFill([
            'last_login_at' => now(),
        ])->save();

        /*
         * Selalu langsung menuju route dashboard.
         */
        return redirect()
            ->route('dashboard')
            ->with(
                'success',
                'Login berhasil. Selamat datang, '
                . $user->name
                . '.'
            );
    }

    /**
     * Memastikan percobaan login belum melewati batas.
     *
     * @throws ValidationException
     */
    private function ensureIsNotRateLimited(
        string $throttleKey
    ): void {
        if (
            ! RateLimiter::tooManyAttempts(
                $throttleKey,
                self::MAX_LOGIN_ATTEMPTS
            )
        ) {
            return;
        }

        $seconds = RateLimiter::availableIn($throttleKey);

        throw ValidationException::withMessages([
            'email' => sprintf(
                'Terlalu banyak percobaan login. '
                . 'Coba kembali dalam %d detik.',
                $seconds
            ),
        ]);
    }

    /**
     * Menangani email atau password yang tidak sesuai.
     *
     * @throws ValidationException
     */
    private function failCredentials(
        string $throttleKey,
        string $message
    ): never {
        RateLimiter::hit(
            $throttleKey,
            self::LOGIN_DECAY_SECONDS
        );

        throw ValidationException::withMessages([
            'email' => $message,
        ]);
    }

    /**
     * Menolak akses ketika kredensial benar,
     * tetapi status atau role tidak sesuai.
     *
     * @throws ValidationException
     */
    private function denyAccess(
        string $throttleKey,
        string $message
    ): never {
        RateLimiter::clear($throttleKey);

        throw ValidationException::withMessages([
            'email' => $message,
        ]);
    }

    /**
     * Pesan detail hanya ditampilkan pada environment local.
     */
    private function developmentMessage(
        string $message
    ): string {
        if (app()->isLocal()) {
            return $message;
        }

        return 'Email atau password yang dimasukkan tidak sesuai.';
    }

    /**
     * Membuat kunci rate limiter.
     */
    private function throttleKey(
        string $email,
        string $ipAddress
    ): string {
        $identity = Str::lower(trim($email))
            . '|'
            . $ipAddress;

        return 'login:' . hash(
            'sha256',
            $identity
        );
    }

    /**
     * Memproses logout pengguna.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

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
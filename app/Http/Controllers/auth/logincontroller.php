<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Display the login form.
     */
    public function create(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        $roles = Role::query()
            ->select(['id', 'name'])
            ->orderBy('id')
            ->get();

        return view('auth.login', compact('roles'));
    }

    /**
     * Process the login request.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:150'],
            'password' => ['required', 'string'],
            'role_id' => ['required', 'integer', 'exists:roles,id'],
            'remember' => ['nullable', 'boolean'],
        ]);

        $email = strtolower(trim($validated['email']));
        $user = User::query()->where('email', $email)->first();

        if (! $user) {
            return back()
                ->withInput()
                ->withErrors(['email' => 'Email tidak ditemukan.']);
        }

        if (empty($user->password)) {
            return back()
                ->withInput()
                ->withErrors(['email' => 'Password belum tersedia.']);
        }

        try {
            $passwordValid = Hash::check($validated['password'], $user->password);
        } catch (\InvalidArgumentException|\RuntimeException) {
            return back()
                ->withInput()
                ->withErrors([
                    'password' => 'Password akun belum menggunakan hash yang valid. Silakan reset password.',
                ]);
        }

        if (! $passwordValid) {
            return back()
                ->withInput()
                ->withErrors(['password' => 'Password tidak sesuai.']);
        }

        if (Hash::needsRehash($user->password)) {
            $user->forceFill([
                'password' => Hash::make($validated['password']),
            ])->save();
        }

        if (! $user->isActive()) {
            return back()
                ->withInput()
                ->withErrors(['email' => 'Akun tidak aktif atau telah dinonaktifkan.']);
        }

        $role = $user->roles()
            ->whereKey($validated['role_id'])
            ->first();

        if (! $role) {
            return back()
                ->withInput()
                ->withErrors(['role_id' => 'Hak akses yang dipilih tidak terhubung ke akun ini.']);
        }

        Auth::guard('web')->login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        session([
            'active_role_id' => $role->id,
            'active_role_name' => $role->name,
        ]);

        $user->updateLoginInfo();

        return redirect()
            ->route('dashboard')
            ->with('success', 'Login berhasil sebagai ' . $role->name);
    }

    /**
     * Log the user out.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->forget([
            'active_role_id',
            'active_role_name',
        ]);

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('success', 'Berhasil logout.');
    }

    public function showLoginForm(): View|RedirectResponse
    {
        return $this->create();
    }

    public function login(Request $request): RedirectResponse
    {
        return $this->store($request);
    }

    public function logout(Request $request): RedirectResponse
    {
        return $this->destroy($request);
    }
}

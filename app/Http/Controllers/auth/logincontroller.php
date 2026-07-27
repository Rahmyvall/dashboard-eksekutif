<?php

declare (strict_types = 1);

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
     * Halaman Login
     */
    public function create(): View | RedirectResponse
    {

        if (Auth::check()) {

            return redirect()
                ->route('dashboard');

        }

        $roles = Role::query()

            ->select([
                'id',
                'name',
            ])

            ->orderBy('id')

            ->get();

        return view(
            'auth.login',
            compact('roles')
        );

    }

    /**
     * Proses Login
     */
    public function store(
        Request $request
    ): RedirectResponse {

        $request->merge([

            'email' => strtolower(
                trim((string) $request->email)
            ),

        ]);

        $validated = $request->validate([

            'email'    => [

                'required',

                'email',

                'max:150',

            ],

            'password' => [

                'required',

                'string',

            ],

            'role_id'  => [

                'required',

                'exists:roles,id',

            ],

            'remember' => [

                'nullable',

                'boolean',

            ],

        ]);

        /*
        |--------------------------------------------------------------------------
        | Reset Session Role
        |--------------------------------------------------------------------------
        */

        $request->session()->forget([

            'active_role_id',

            'active_role_name',

        ]);

        /*
        |--------------------------------------------------------------------------
        | Cari User
        |--------------------------------------------------------------------------
        */

        $user = User::query()

            ->where(
                'email',
                $validated['email']
            )

            ->first();

        if (! $user) {

            return back()

                ->withInput()

                ->withErrors([

                    'email' =>
                    'Email tidak ditemukan.',

                ]);

        }

        /*
        /*
|--------------------------------------------------------------------------
| Validasi Password
|--------------------------------------------------------------------------
*/

        $storedPassword = $user->password;

        if (

            empty($storedPassword)

            ||

            ! Hash::needsRehash($storedPassword)

            &&

            ! Hash::check(

                $validated['password'],

                $storedPassword

            )

        ) {

            return back()

                ->withInput()

                ->withErrors([

                    'email' =>
                    'Password tidak sesuai.',

                ]);

        }

        /*
        |--------------------------------------------------------------------------
        | Status User
        |--------------------------------------------------------------------------
        */

        if (

            $user->status !== User::STATUS_ACTIVE

        ) {

            return back()

                ->withErrors([

                    'email' =>
                    'Akun tidak aktif.',

                ]);

        }

        /*
        |--------------------------------------------------------------------------
        | Login
        |--------------------------------------------------------------------------
        */

        Auth::guard('web')->login(

            $user,

            $request->boolean('remember')

        );

        $request
            ->session()
            ->regenerate();

        /*
        |--------------------------------------------------------------------------
        | Cek Role User
        |--------------------------------------------------------------------------
        */

        $role = $user

            ->roles()

            ->where(

                'roles.id',

                $validated['role_id']

            )

            ->first();

        if (! $role) {

            Auth::guard('web')->logout();

            return back()

                ->withErrors([

                    'role_id' =>
                    'Role tidak tersedia untuk akun ini.',

                ]);

        }

        /*
        |--------------------------------------------------------------------------
        | Simpan Role Aktif
        |--------------------------------------------------------------------------
        */

        session([

            'active_role_id'   => $role->id,

            'active_role_name' => $role->name,

        ]);

        /*
        |--------------------------------------------------------------------------
        | Update Login
        |--------------------------------------------------------------------------
        */

        $user->update([

            'last_login_at' => now(),

            'last_login_ip' => $request->ip(),

        ]);

        return redirect()

            ->route('dashboard')

            ->with(

                'success',

                'Login berhasil sebagai ' . $role->name

            );

    }

    /**
     * Logout
     */
    public function destroy(
        Request $request
    ): RedirectResponse {

        Auth::guard('web')->logout();

        $request->session()->forget([

            'active_role_id',

            'active_role_name',

        ]);

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()

            ->route('login')

            ->with(

                'success',

                'Berhasil logout.'

            );

    }

    public function showLoginForm(): View | RedirectResponse
    {
        return $this->create();
    }

    public function login(
        Request $request
    ): RedirectResponse {

        return $this->store($request);

    }

    public function logout(
        Request $request
    ): RedirectResponse {

        return $this->destroy($request);

    }

}

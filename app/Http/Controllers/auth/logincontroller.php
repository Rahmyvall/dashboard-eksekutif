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
     * Menampilkan halaman login
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
            [
                'roles' => $roles,
            ]
        );

    }

    /**
     * Support route lama
     */
    public function showLoginForm(): View | RedirectResponse
    {
        return $this->create();
    }

    /**
     * Proses Login
     */
    public function store(
        Request $request
    ): RedirectResponse {

        $request->merge([

            'email' => strtolower(
                trim($request->email)
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
        | Hapus session role sebelumnya
        |--------------------------------------------------------------------------
        */

        $request->session()->forget([

            'active_role_id',

            'active_role_name',

        ]);

        /*
        |--------------------------------------------------------------------------
        | Cari user
        |--------------------------------------------------------------------------
        */

        $user = User::where(

            'email',

            $validated['email']

        )->first();

        if (! $user) {

            return back()

                ->withInput()

                ->withErrors([

                    'email' =>
                    'Email tidak ditemukan.',

                ]);

        }

        /*
        |--------------------------------------------------------------------------
        | Validasi password bcrypt
        |--------------------------------------------------------------------------
        */

        if (
            ! Hash::check(
                $validated['password'],
                $user->password
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
        | Status user
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
        | Login User
        |--------------------------------------------------------------------------
        */

        Auth::login(

            $user,

            $request->boolean('remember')

        );

        $request
            ->session()
            ->regenerate();

        /*
        |--------------------------------------------------------------------------
        | Validasi Role User
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

            Auth::logout();

            return back()

                ->withErrors([

                    'role_id' =>
                    'Anda tidak memiliki akses role tersebut.',

                ]);

        }

        /*
        |--------------------------------------------------------------------------
        | Simpan Role Aktif
        |--------------------------------------------------------------------------
        */

        session([

            'active_role_id'   =>
            $role->id,

            'active_role_name' =>
            $role->name,

        ]);

        /*
        |--------------------------------------------------------------------------
        | Update Login
        |--------------------------------------------------------------------------
        */

        $user->update([

            'last_login_at' => now(),

            'last_login_ip' => request()->ip(),

        ]);

        return redirect()

            ->route('dashboard')

            ->with(

                'success',

                'Login berhasil sebagai ' . $role->name

            );

    }

    /**
     * Support route login lama
     */
    public function login(
        Request $request
    ): RedirectResponse {

        return $this->store($request);

    }

    /**
     * Logout
     */
    public function logout(
        Request $request
    ): RedirectResponse {

        return $this->destroy($request);

    }

    /**
     * Destroy session logout
     *
     * Digunakan oleh route:
     * LoginController@destroy
     */
    public function destroy(
        Request $request
    ): RedirectResponse {

        Auth::logout();

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

}

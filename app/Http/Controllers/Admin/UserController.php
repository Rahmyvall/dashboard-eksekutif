<?php

declare (strict_types = 1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {

        $query = User::query();

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where(
                    'name',
                    'ILIKE',
                    "%{$search}%"
                )

                    ->orWhere(
                        'email',
                        'ILIKE',
                        "%{$search}%"
                    );

            });

        }

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );

        }

        $users = $query

            ->latest()

            ->paginate(10)

            ->withQueryString();

        $statistics = [

            'total_users'    => User::count(),

            'active_users'   => User::where(
                'status',
                User::STATUS_ACTIVE
            )->count(),

            'inactive_users' => User::where(
                'status',
                User::STATUS_INACTIVE
            )->count(),

            'login_activity' => User::whereNotNull(
                'last_login_at'
            )->count(),

        ];

        return view(
            'super-admin.users.index',
            compact(
                'users',
                'statistics'
            )
        );

    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create()
    {

        $roles = Role::orderBy(
            'name'
        )->get();

        return view(
            'super-admin.users.create',
            [

                'roles'    => $roles,

                'statuses' => User::statuses(),

            ]
        );

    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {

        $validated = $request->validate([

            'name'     => [

                'required',

                'string',

                'max:150',

            ],

            'email'    => [

                'required',

                'email',

                'max:150',

                'unique:users,email',

            ],

            'password' => [

                'required',

                'string',

                'min:8',

            ],

            'status'   => [

                'required',

                Rule::in(
                    User::statuses()
                ),

            ],

            'role_id'  => [

                'required',

                'exists:roles,id',

            ],

        ]);

        $user = User::create([

            'name'     => $validated['name'],

            'email'    => $validated['email'],

            'password' => Hash::make(
                $validated['password']
            ),

            'status'   => $validated['status'],

        ]);

        $user->roles()->attach(
            $validated['role_id']
        );

        return redirect()

            ->route(
                'super-admin.users.index'
            )

            ->with(
                'success',
                'Pengguna berhasil ditambahkan.'
            );

    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(User $user)
    {

        return view(
            'super-admin.users.show',
            compact('user')
        );

    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(User $user)
    {

        $roles = Role::orderBy(
            'name'
        )->get();

        return view(
            'super-admin.users.edit',
            [

                'user'     => $user,

                'roles'    => $roles,

                'statuses' => User::statuses(),

            ]
        );

    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        User $user
    ) {

        $validated = $request->validate([

            'name'     => [
                'required',
                'string',
                'max:150',
            ],

            'email'    => [

                'required',

                'email',

                Rule::unique('users')
                    ->ignore($user->id),

            ],

            'password' => [

                'nullable',

                'string',

                'min:8',

            ],

            'status'   => [

                'required',

                Rule::in(
                    User::statuses()
                ),

            ],

            'role_id'  => [

                'required',

                'exists:roles,id',

            ],

        ]);

        $data = [

            'name'   => $validated['name'],

            'email'  => $validated['email'],

            'status' => $validated['status'],

        ];

        if (
            filled($validated['password'])
        ) {

            $data['password'] =
            Hash::make(
                $validated['password']
            );

        }

        $user->update($data);

        $user->roles()->sync([

            $validated['role_id'],

        ]);

        return redirect()

            ->route(
                'super-admin.users.index'
            )

            ->with(
                'success',
                'Data pengguna berhasil diperbarui.'
            );

    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(User $user)
    {

        if (
            Auth::id() === $user->id
        ) {

            return back()

                ->with(
                    'error',
                    'Tidak dapat menghapus akun sendiri.'
                );

        }

        $user->delete();

        return redirect()

            ->route(
                'super-admin.users.index'
            )

            ->with(
                'success',
                'Pengguna berhasil dihapus.'
            );

    }

    /*
    |--------------------------------------------------------------------------
    | TRASH
    |--------------------------------------------------------------------------
    */

    public function trash()
    {

        $users = User::onlyTrashed()

            ->latest()

            ->paginate(10);

        return view(
            'super-admin.users.trash',
            compact('users')
        );

    }

    /*
    |--------------------------------------------------------------------------
    | RESTORE
    |--------------------------------------------------------------------------
    */

    public function restore($id)
    {

        User::withTrashed()

            ->findOrFail($id)

            ->restore();

        return redirect()

            ->route(
                'super-admin.users.trash'
            )

            ->with(
                'success',
                'Pengguna berhasil dikembalikan.'
            );

    }

    /*
    |--------------------------------------------------------------------------
    | FORCE DELETE
    |--------------------------------------------------------------------------
    */

    public function forceDelete($id)
    {

        User::withTrashed()

            ->findOrFail($id)

            ->forceDelete();

        return redirect()

            ->route(
                'super-admin.users.trash'
            )

            ->with(
                'success',
                'Pengguna dihapus permanen.'
            );

    }

}

<?php

declare (strict_types = 1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

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

        /*
        |--------------------------------------------------------------------------
        | FILTER STATUS
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );

        }

        $users = $query

            ->latest()

            ->paginate(3)

            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | STATISTIC
        |--------------------------------------------------------------------------
        */

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

            'admin.users.index',

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
        $roles = Role::query()
            ->orderBy('name', 'asc')
            ->get();

        return view(
            'admin.users.create',
            [
                'roles'    => $roles,

                'statuses' => [
                    User::STATUS_ACTIVE,
                    User::STATUS_INACTIVE,
                    User::STATUS_SUSPENDED,
                ],
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

                Rule::in([

                    User::STATUS_ACTIVE,

                    User::STATUS_INACTIVE,

                    User::STATUS_SUSPENDED,

                ]),

            ],

        ]);

        User::create($validated);

        return redirect()

            ->route('admin.users.index')

            ->with(

                'success',

                'Pengguna berhasil ditambahkan'

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

            'admin.users.show',

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

        return view(

            'admin.users.edit',

            [

                'user'     => $user,

                'statuses' => [

                    User::STATUS_ACTIVE,

                    User::STATUS_INACTIVE,

                    User::STATUS_SUSPENDED,

                ],

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

                Rule::in([

                    User::STATUS_ACTIVE,

                    User::STATUS_INACTIVE,

                    User::STATUS_SUSPENDED,

                ]),

            ],

        ]);

        if (

            empty($validated['password'])

        ) {

            unset(

                $validated['password']

            );

        }

        $user->update($validated);

        return redirect()

            ->route('admin.users.index')

            ->with(

                'success',

                'Data pengguna berhasil diperbarui'

            );

    }

    /*
    |--------------------------------------------------------------------------
    | DELETE SOFT DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(User $user)
    {

        if (Auth::id() === $user->id) {

            return back()

                ->with(

                    'error',

                    'Tidak dapat menghapus akun sendiri'

                );

        }

        $user->delete();

        return redirect()

            ->route('admin.users.index')

            ->with(

                'success',

                'Pengguna berhasil dihapus'

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

            'admin.users.trash',

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

        $user = User::withTrashed()

            ->findOrFail($id);

        $user->restore();

        return redirect()

            ->route('admin.users.trash')

            ->with(

                'success',

                'Pengguna berhasil dikembalikan'

            );

    }

    /*
    |--------------------------------------------------------------------------
    | FORCE DELETE
    |--------------------------------------------------------------------------
    */

    public function forceDelete($id)
    {

        $user = User::withTrashed()

            ->findOrFail($id);

        $user->forceDelete();

        return redirect()

            ->route('admin.users.trash')

            ->with(

                'success',

                'Pengguna dihapus permanen'

            );

    }

}

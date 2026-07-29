<?php

declare (strict_types = 1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RoleController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request): View
    {

        $filters = $request->validate([

            'search' => [
                'nullable',
                'string',
                'max:100',
            ],

        ]);

        $query = Role::query()
            ->withCount([
                'users',
                'permissions',
            ]);

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if (! empty($filters['search'])) {

            $keyword = strtolower(
                trim($filters['search'])
            );

            $query->where(function (Builder $q) use ($keyword) {

                $q->whereRaw(
                    'LOWER(name) LIKE ?',
                    [
                        "%{$keyword}%",
                    ]
                )

                    ->orWhereRaw(
                        'LOWER(guard_name) LIKE ?',
                        [
                            "%{$keyword}%",
                        ]
                    );

            });

        }

        /*
        |--------------------------------------------------------------------------
        | DATA
        |--------------------------------------------------------------------------
        */

        $roles = $query

            ->orderBy(
                'name',
                'asc'
            )

            ->paginate(10)

            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | STATISTIC
        |--------------------------------------------------------------------------
        */

        $stats = [

            'total_roles' =>
            Role::count(),

        ];

        return view(
            'super-admin.roles.index',
            compact(
                'roles',
                'stats'
            )
        );

    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(): View
    {
        return view(
            'super-admin.roles.create'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request): RedirectResponse
    {

        $validated = $request->validate([

            'name'       => [

                'required',

                'string',

                'max:255',

                'regex:/^[a-z0-9_]+$/',

                Rule::unique(
                    'roles',
                    'name'
                ),

            ],

            'guard_name' => [

                'required',

                'string',

                'max:255',

            ],

        ]);

        DB::transaction(function () use ($validated) {

            Role::create(
                $validated
            );

        });

        return redirect()

            ->route(
                'super-admin.roles.index'
            )

            ->with(
                'success',
                'Role berhasil ditambahkan.'
            );

    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(Role $role): View
    {

        $role->loadCount([

            'users',

            'permissions',

        ]);

        $role->load([
            'permissions',
        ]);

        return view(
            'super-admin.roles.show',
            compact('role')
        );

    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(Role $role): View
    {

        return view(
            'super-admin.roles.edit',
            compact('role')
        );

    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Role $role
    ): RedirectResponse {

        $validated = $request->validate([

            'name'       => [

                'required',

                'string',

                'max:255',

                'regex:/^[a-z0-9_]+$/',

                Rule::unique(
                    'roles',
                    'name'
                )
                    ->ignore(
                        $role->id
                    ),

            ],

            'guard_name' => [

                'required',

                'string',

                'max:255',

            ],

        ]);

        DB::transaction(function () use (
            $role,
            $validated
        ) {

            $role->update(
                $validated
            );

        });

        return redirect()

            ->route(
                'super-admin.roles.index'
            )

            ->with(
                'success',
                'Role berhasil diperbarui.'
            );

    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(Role $role): RedirectResponse
    {

        if ($role->users()->exists()) {

            return back()

                ->with(
                    'error',
                    'Role masih digunakan user.'
                );

        }

        DB::transaction(function () use ($role) {

            $role
                ->permissions()
                ->detach();

            $role->delete();

        });

        return redirect()

            ->route(
                'super-admin.roles.index'
            )

            ->with(
                'success',
                'Role berhasil dihapus.'
            );

    }

}

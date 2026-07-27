<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    /**
     * Display role list
     */
    public function index(Request $request)
    {
        $query = Role::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'ILIKE', "%{$search}%")
                    ->orWhere('display_name', 'ILIKE', "%{$search}%")
                    ->orWhere('description', 'ILIKE', "%{$search}%");
            });
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $roles = $query
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        // Statistics
        $stats = [
            'total_roles'    => Role::count(),

            'active_roles'   => Role::where(
                'status',
                'active'
            )->count(),

            'inactive_roles' => Role::where(
                'status',
                'inactive'
            )->count(),

            'system_roles'   => Role::where(
                'is_system',
                true
            )->count(),

            'custom_roles'   => Role::where(
                'is_system',
                false
            )->count(),
        ];

        return view(
            'admin.roles.index',
            compact(
                'roles',
                'stats'
            )
        );
    }

    /**
     * Create role form
     */
    public function create()
    {
        return view(
            'admin.roles.create'
        );
    }

    /**
     * Store new role
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'name'         => [
                'required',
                'string',
                'max:80',
                Rule::unique('roles')
                    ->where(function ($query) use ($request) {
                        return $query->where(
                            'guard_name',
                            $request->guard_name ?? 'web'
                        );
                    }),
            ],

            'display_name' => [
                'nullable',
                'string',
                'max:100',
            ],

            'guard_name'   => [
                'required',
                'string',
                'max:50',
            ],

            'description'  => [
                'nullable',
                'string',
            ],

            'status'       => [
                'required',
                Rule::in([
                    'active',
                    'inactive',
                ]),
            ],

            'sort_order'   => [
                'nullable',
                'integer',
            ],

        ]);

        Role::create([
             ...$validated,
            'is_system' => false,
        ]);

        return redirect()
            ->route('admin.roles.index')
            ->with(
                'success',
                'Role berhasil ditambahkan.'
            );
    }

    /**
     * Show role detail
     */
    public function show(Role $role)
    {
        return view(
            'admin.roles.show',
            compact('role')
        );
    }

    /**
     * Edit role
     */
    public function edit(Role $role)
    {
        return view(
            'admin.roles.edit',
            compact('role')
        );
    }

    /**
     * Update role
     */
    public function update(
        Request $request,
        Role $role
    ) {

        $validated = $request->validate([

            'name'         => [
                'required',
                'string',
                'max:80',

                Rule::unique('roles')
                    ->where(function ($query) use ($request) {

                        return $query->where(
                            'guard_name',
                            $request->guard_name ?? 'web'
                        );

                    })
                    ->ignore($role->id),

            ],

            'display_name' => [
                'nullable',
                'string',
                'max:100',
            ],

            'guard_name'   => [
                'required',
                'string',
                'max:50',
            ],

            'description'  => [
                'nullable',
                'string',
            ],

            'status'       => [
                'required',
                Rule::in([
                    'active',
                    'inactive',
                ]),
            ],

            'sort_order'   => [
                'nullable',
                'integer',
            ],

        ]);

        // Role sistem tidak boleh diganti nama
        if ($role->is_system) {

            $validated['name'] = $role->name;

        }

        $role->update(
            $validated
        );

        return redirect()
            ->route('admin.roles.index')
            ->with(
                'success',
                'Role berhasil diperbarui.'
            );

    }

    /**
     * Delete role
     */
    public function destroy(Role $role)
    {

        // Proteksi role bawaan
        if ($role->is_system) {

            return back()
                ->with(
                    'error',
                    'Role sistem tidak dapat dihapus.'
                );

        }

        $role->delete();

        return redirect()
            ->route('admin.roles.index')
            ->with(
                'success',
                'Role berhasil dihapus.'
            );

    }

}

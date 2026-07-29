<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RoleApiController extends Controller
{
    // List all roles
    public function index(Request $request): JsonResponse
    {
        $roles = Role::all();

        return response()->json([
            'success' => true,
            'data'    => $roles,
        ]);
    }

    // Show role details
    public function show(Role $role): JsonResponse
    {
        $role->load(['permissions', 'users']);

        return response()->json([
            'success' => true,
            'data'    => $role,
        ]);
    }

    // Create new role
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'       => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9_]+$/',
                Rule::unique('roles', 'name'),
            ],
            'guard_name' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        $role = DB::transaction(function () use ($validated) {
            return Role::create($validated);
        });

        return response()->json([
            'success' => true,
            'message' => 'Role berhasil dibuat.',
            'data'    => $role,
        ], 201);
    }

    // Update role
    public function update(Request $request, Role $role): JsonResponse
    {
        $validated = $request->validate([
            'name'       => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9_]+$/',
                Rule::unique('roles', 'name')->ignore($role->id),
            ],
            'guard_name' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        DB::transaction(function () use ($role, $validated) {
            $role->update($validated);
        });

        return response()->json([
            'success' => true,
            'message' => 'Role berhasil diperbarui.',
            'data'    => $role,
        ]);
    }

    // Delete role
    public function destroy(Role $role): JsonResponse
    {
        if ($role->users()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Role masih digunakan oleh pengguna.',
            ], 400);
        }

        DB::transaction(function () use ($role) {
            $role->permissions()->detach();
            $role->delete();
        });

        return response()->json([
            'success' => true,
            'message' => 'Role berhasil dihapus.',
        ]);
    }
}

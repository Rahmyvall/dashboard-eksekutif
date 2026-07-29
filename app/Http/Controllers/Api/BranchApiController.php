<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class BranchApiController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | List All Branch
    |--------------------------------------------------------------------------
    */

    public function index(Request $request): JsonResponse
    {

        $branches = Branch::with([
            'manager:id,name,email',
        ])
            ->latest()
            ->get();

        return response()->json([

            'success' => true,

            'data'    => $branches,

        ]);

    }

    /*
    |--------------------------------------------------------------------------
    | Show Branch Details
    |--------------------------------------------------------------------------
    */

    public function show(Branch $branch): JsonResponse
    {

        $branch->load([

            'manager:id,name,email',

            'approvalLogs',

        ]);

        return response()->json([

            'success' => true,

            'data'    => $branch,

        ]);

    }

    /*
    |--------------------------------------------------------------------------
    | Create New Branch
    |--------------------------------------------------------------------------
    */

    public function store(Request $request): JsonResponse
    {

        $validated = $request->validate([

            'branch_code' => [

                'required',

                'string',

                'max:50',

                Rule::unique(
                    'branches',
                    'branch_code'
                ),

            ],

            'branch_name' => [

                'required',

                'string',

                'max:100',

            ],

            'address'     => [

                'required',

                'string',

            ],

            'phone'       => [

                'required',

                'string',

                'max:20',

            ],

            'email'       => [

                'required',

                'email',

                'max:100',

                Rule::unique(
                    'branches',
                    'email'
                ),

            ],

            'manager_id'  => [

                'nullable',

                Rule::exists(
                    'users',
                    'id'
                ),

            ],

            'status'      => [

                'nullable',

                'boolean',

            ],

        ]);

        $branch = DB::transaction(function () use ($validated) {

            return Branch::create([

                'branch_code' => $validated['branch_code'],

                'branch_name' => $validated['branch_name'],

                'address'     => $validated['address'],

                'phone'       => $validated['phone'],

                'email'       => $validated['email'],

                'manager_id'  => $validated['manager_id'] ?? null,

                'status'      => $validated['status'] ?? true,

            ]);

        });

        return response()->json([

            'success' => true,

            'message' => 'Branch berhasil dibuat.',

            'data'    => $branch,

        ], 201);

    }

    /*
    |--------------------------------------------------------------------------
    | Update Branch
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Branch $branch
    ): JsonResponse {

        $validated = $request->validate([

            'branch_code' => [

                'sometimes',

                'string',

                'max:50',

                Rule::unique(
                    'branches',
                    'branch_code'
                )->ignore($branch->id),

            ],

            'branch_name' => [

                'sometimes',

                'string',

                'max:100',

            ],

            'address'     => [

                'sometimes',

                'string',

            ],

            'phone'       => [

                'sometimes',

                'string',

                'max:20',

            ],

            'email'       => [

                'sometimes',

                'email',

                'max:100',

                Rule::unique(
                    'branches',
                    'email'
                )->ignore($branch->id),

            ],

            'manager_id'  => [

                'nullable',

                Rule::exists(
                    'users',
                    'id'
                ),

            ],

            'status'      => [

                'sometimes',

                'boolean',

            ],

        ]);

        DB::transaction(function () use (
            $branch,
            $validated
        ) {

            $branch->update($validated);

        });

        return response()->json([

            'success' => true,

            'message' => 'Branch berhasil diperbarui.',

            'data'    => $branch,

        ]);

    }

    /*
    |--------------------------------------------------------------------------
    | Delete Branch
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Branch $branch
    ): JsonResponse {

        DB::transaction(function () use ($branch) {

            $branch->delete();

        });

        return response()->json([

            'success' => true,

            'message' => 'Branch berhasil dihapus.',

        ]);

    }

    /*
    |--------------------------------------------------------------------------
    | Restore Branch
    |--------------------------------------------------------------------------
    */

    public function restore(
        int $id
    ): JsonResponse {

        $branch = Branch::withTrashed()
            ->findOrFail($id);

        DB::transaction(function () use ($branch) {

            $branch->restore();

        });

        return response()->json([

            'success' => true,

            'message' => 'Branch berhasil dikembalikan.',

            'data'    => $branch,

        ]);

    }

    /*
    |--------------------------------------------------------------------------
    | Update Branch Status
    |--------------------------------------------------------------------------
    */

    public function updateStatus(
        Request $request,
        Branch $branch
    ): JsonResponse {

        $validated = $request->validate([

            'status' => [

                'required',

                'boolean',

            ],

        ]);

        $branch->update([

            'status' => $validated['status'],

        ]);

        return response()->json([

            'success' => true,

            'message' => 'Status branch berhasil diperbarui.',

            'data'    => $branch,

        ]);

    }

}

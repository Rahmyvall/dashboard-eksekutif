<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Throwable;

class DepartmentController extends Controller
{
    /**
     * Menampilkan daftar department.
     */
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('search', ''));
        $status = $request->input('status');

        $departments = Department::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('code', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when(
                in_array($status, ['active', 'inactive'], true),
                fn($query) => $query->where('status', $status)
            )
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view(
            'super-admin.departments.index',
            compact('departments', 'search', 'status')
        );
    }

    /**
     * Menampilkan form tambah department.
     *
     * Hanya Super Admin.
     */
    public function create(): View
    {
        return view('super-admin.departments.create');
    }

    /**
     * Menyimpan department baru.
     *
     * Hanya Super Admin.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate(
            [
                'code'        => [
                    'required',
                    'string',
                    'max:30',
                    Rule::unique('departments', 'code'),
                ],

                'name'        => [
                    'required',
                    'string',
                    'max:150',
                ],

                'description' => [
                    'nullable',
                    'string',
                ],

                'status'      => [
                    'required',
                    Rule::in(['active', 'inactive']),
                ],
            ],
            [
                'code.required'      => 'Kode department wajib diisi.',
                'code.string'        => 'Kode department harus berupa teks.',
                'code.max'           => 'Kode department maksimal 30 karakter.',
                'code.unique'        => 'Kode department sudah digunakan.',

                'name.required'      => 'Nama department wajib diisi.',
                'name.string'        => 'Nama department harus berupa teks.',
                'name.max'           => 'Nama department maksimal 150 karakter.',

                'description.string' => 'Deskripsi harus berupa teks.',

                'status.required'    => 'Status department wajib dipilih.',
                'status.in'          => 'Status department tidak valid.',
            ]
        );

        try {
            DB::transaction(function () use ($validated): void {
                Department::create([
                    'code'        => strtoupper(trim($validated['code'])),

                    'name'        => trim($validated['name']),

                    'description' => filled($validated['description'] ?? null)
                        ? trim($validated['description'])
                        : null,

                    'status'      => $validated['status'],
                ]);
            });

            return redirect()
                ->route('super-admin.departments.index')
                ->with(
                    'success',
                    'Department berhasil ditambahkan.'
                );
        } catch (Throwable $exception) {
            report($exception);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Department gagal ditambahkan. Silakan coba kembali.'
                );
        }
    }

    /**
     * Menampilkan detail department.
     *
     * Dapat dibuka oleh:
     * - Super Admin
     * - Direktur
     * - Manager
     * - HRD
     * - Auditor
     */
    public function show(Department $department): View
    {
        return view(
            'super-admin.departments.show',
            compact('department')
        );
    }

    /**
     * Menampilkan form edit department.
     *
     * Hanya Super Admin.
     */
    public function edit(Department $department): View
    {
        return view(
            'super-admin.departments.edit',
            compact('department')
        );
    }

    /**
     * Memperbarui department.
     *
     * Hanya Super Admin.
     */
    public function update(
        Request $request,
        Department $department
    ): RedirectResponse {
        $validated = $request->validate(
            [
                'code'        => [
                    'required',
                    'string',
                    'max:30',
                    Rule::unique('departments', 'code')
                        ->ignore($department->getKey()),
                ],

                'name'        => [
                    'required',
                    'string',
                    'max:150',
                ],

                'description' => [
                    'nullable',
                    'string',
                ],

                'status'      => [
                    'required',
                    Rule::in(['active', 'inactive']),
                ],
            ],
            [
                'code.required'      => 'Kode department wajib diisi.',
                'code.string'        => 'Kode department harus berupa teks.',
                'code.max'           => 'Kode department maksimal 30 karakter.',
                'code.unique'        => 'Kode department sudah digunakan.',

                'name.required'      => 'Nama department wajib diisi.',
                'name.string'        => 'Nama department harus berupa teks.',
                'name.max'           => 'Nama department maksimal 150 karakter.',

                'description.string' => 'Deskripsi harus berupa teks.',

                'status.required'    => 'Status department wajib dipilih.',
                'status.in'          => 'Status department tidak valid.',
            ]
        );

        try {
            DB::transaction(function () use (
                $validated,
                $department
            ): void {
                $department->update([
                    'code'        => strtoupper(trim($validated['code'])),

                    'name'        => trim($validated['name']),

                    'description' => filled($validated['description'] ?? null)
                        ? trim($validated['description'])
                        : null,

                    'status'      => $validated['status'],
                ]);
            });

            return redirect()
                ->route('super-admin.departments.index')
                ->with(
                    'success',
                    'Department berhasil diperbarui.'
                );
        } catch (Throwable $exception) {
            report($exception);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Department gagal diperbarui. Silakan coba kembali.'
                );
        }
    }

    /**
     * Menghapus department menggunakan soft delete.
     *
     * Hanya Super Admin.
     */
    public function destroy(
        Department $department
    ): RedirectResponse {
        try {
            DB::transaction(function () use ($department): void {
                $department->delete();
            });

            return redirect()
                ->route('super-admin.departments.index')
                ->with(
                    'success',
                    'Department berhasil dihapus.'
                );
        } catch (Throwable $exception) {
            report($exception);

            return back()->with(
                'error',
                'Department gagal dihapus. Department mungkin masih digunakan oleh data lain.'
            );
        }
    }

    /**
     * Menampilkan department yang sudah dihapus.
     *
     * Hanya Super Admin.
     */
    public function trash(Request $request): View
    {
        $search = trim((string) $request->input('search', ''));

        $departments = Department::onlyTrashed()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('code', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('deleted_at')
            ->paginate(10)
            ->withQueryString();

        return view(
            'super-admin.departments.trash',
            compact('departments', 'search')
        );
    }

    /**
     * Mengembalikan department yang sudah dihapus.
     *
     * Hanya Super Admin.
     */
    public function restore(int $id): RedirectResponse
    {
        try {
            DB::transaction(function () use ($id): void {
                $department = Department::onlyTrashed()
                    ->findOrFail($id);

                $department->restore();
            });

            return redirect()
                ->route('super-admin.departments.index')
                ->with(
                    'success',
                    'Department berhasil dikembalikan.'
                );
        } catch (Throwable $exception) {
            report($exception);

            return back()->with(
                'error',
                'Department gagal dikembalikan.'
            );
        }
    }

    /**
     * Menghapus department secara permanen.
     *
     * Hanya Super Admin.
     */
    public function forceDelete(int $id): RedirectResponse
    {
        try {
            DB::transaction(function () use ($id): void {
                $department = Department::onlyTrashed()
                    ->findOrFail($id);

                $department->forceDelete();
            });

            return redirect()
                ->route('super-admin.departments.trash')
                ->with(
                    'success',
                    'Department berhasil dihapus secara permanen.'
                );
        } catch (Throwable $exception) {
            report($exception);

            return back()->with(
                'error',
                'Department gagal dihapus secara permanen.'
            );
        }
    }
}

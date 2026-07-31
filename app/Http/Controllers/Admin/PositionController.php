<?php

declare (strict_types = 1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePositionRequest;
use App\Http\Requests\UpdatePositionRequest;
use App\Models\Department;
use App\Models\Position;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use RuntimeException;
use Throwable;

class PositionController extends Controller
{
    /**
     * Menampilkan daftar jabatan.
     */
    public function index(Request $request): View
    {
        $search       = trim((string) $request->query('search', ''));
        $status       = trim((string) $request->query('status', ''));
        $departmentId = $request->query('department_id');
        $level        = $request->query('level');

        $validStatuses = ['active', 'inactive'];

        $positions = Position::query()
            ->with([
                'department:id,code,name,status',
            ])
            ->when(
                $search !== '',
                function (Builder $query) use ($search): void {
                    $query->where(
                        function (Builder $subQuery) use ($search): void {
                            $subQuery
                                ->where('code', 'ILIKE', "%{$search}%")
                                ->orWhere('name', 'ILIKE', "%{$search}%")
                                ->orWhere('description', 'ILIKE', "%{$search}%")
                                ->orWhereHas(
                                    'department',
                                    function (Builder $departmentQuery) use ($search): void {
                                        $departmentQuery
                                            ->where('code', 'ILIKE', "%{$search}%")
                                            ->orWhere('name', 'ILIKE', "%{$search}%");
                                    }
                                );
                        }
                    );
                }
            )
            ->when(
                in_array($status, $validStatuses, true),
                fn(Builder $query): Builder => $query->where('status', $status)
            )
            ->when(
                filled($departmentId) && ctype_digit((string) $departmentId),
                fn(Builder $query): Builder => $query->where(
                    'department_id',
                    (int) $departmentId
                )
            )
            ->when(
                filled($level) && ctype_digit((string) $level),
                fn(Builder $query): Builder => $query->where(
                    'level',
                    (int) $level
                )
            )
            ->orderByDesc('level')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        /*
         * Dipakai oleh filter pada halaman index.
         * Hindari konstanta Department yang mungkin belum dibuat.
         */
        $departments = Department::query()
            ->where('status', 'active')
            ->orderBy('name')
            ->get([
                'id',
                'code',
                'name',
                'status',
            ]);

        $levels = Position::query()
            ->select('level')
            ->distinct()
            ->orderBy('level')
            ->pluck('level');

        return view(
            'super-admin.positions.index',
            compact(
                'positions',
                'departments',
                'levels',
                'search',
                'status',
                'departmentId',
                'level'
            )
        );
    }

    /**
     * Menampilkan formulir tambah jabatan.
     */
    public function create(): View
    {
        $departments = Department::query()
            ->where('status', 'active')
            ->orderBy('name')
            ->get([
                'id',
                'code',
                'name',
                'status',
            ]);

        return view(
            'super-admin.positions.create',
            compact('departments')
        );
    }

    /**
     * Menyimpan jabatan baru.
     */
    public function store(
        StorePositionRequest $request
    ): RedirectResponse {
        $validated = $request->validated();

        try {
            DB::transaction(
                function () use ($validated): void {
                    Position::query()->create([
                        'department_id' => (int) $validated['department_id'],
                        'code'          => strtoupper(trim((string) $validated['code'])),
                        'name'          => trim((string) $validated['name']),
                        'level'         => (int) $validated['level'],
                        'description'   => filled($validated['description'] ?? null)
                            ? trim((string) $validated['description'])
                            : null,
                        'status'        => (string) $validated['status'],
                    ]);
                }
            );

            return redirect()
                ->route('super-admin.positions.index')
                ->with('success', 'Jabatan berhasil ditambahkan.');
        } catch (Throwable $exception) {
            report($exception);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Jabatan gagal ditambahkan. Silakan coba kembali.'
                );
        }
    }

    /**
     * Menampilkan detail jabatan.
     */
    public function show(Position $position): View
    {
        $position->load([
            'department:id,code,name,status,description',
        ]);

        return view(
            'super-admin.positions.show',
            compact('position')
        );
    }

    /**
     * Menampilkan formulir edit jabatan.
     */
    public function edit(Position $position): View
    {
        /*
         * Departemen aktif ditampilkan.
         * Departemen yang sedang dipakai tetap ditampilkan walaupun inactive.
         */
        $departments = Department::query()
            ->where(
                function (Builder $query) use ($position): void {
                    $query
                        ->where('status', 'active')
                        ->orWhereKey($position->department_id);
                }
            )
            ->orderBy('name')
            ->get([
                'id',
                'code',
                'name',
                'status',
            ]);

        $position->load([
            'department:id,code,name,status',
        ]);

        return view(
            'super-admin.positions.edit',
            compact(
                'position',
                'departments'
            )
        );
    }

    /**
     * Memperbarui jabatan.
     */
    public function update(
        UpdatePositionRequest $request,
        Position $position
    ): RedirectResponse {
        $validated = $request->validated();

        try {
            DB::transaction(
                function () use ($validated, $position): void {
                    $position->update([
                        'department_id' => (int) $validated['department_id'],
                        'code'          => strtoupper(trim((string) $validated['code'])),
                        'name'          => trim((string) $validated['name']),
                        'level'         => (int) $validated['level'],
                        'description'   => filled($validated['description'] ?? null)
                            ? trim((string) $validated['description'])
                            : null,
                        'status'        => (string) $validated['status'],
                    ]);
                }
            );

            return redirect()
                ->route(
                    'super-admin.positions.show',
                    $position
                )
                ->with('success', 'Jabatan berhasil diperbarui.');
        } catch (Throwable $exception) {
            report($exception);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Jabatan gagal diperbarui. Silakan coba kembali.'
                );
        }
    }

    /**
     * Menghapus jabatan menggunakan soft delete.
     */
    public function destroy(Position $position): RedirectResponse
    {
        try {
            DB::transaction(
                function () use ($position): void {
                    $position->delete();
                }
            );

            return redirect()
                ->route('super-admin.positions.index')
                ->with('success', 'Jabatan berhasil dipindahkan ke sampah.');
        } catch (Throwable $exception) {
            report($exception);

            return back()->with(
                'error',
                'Jabatan gagal dihapus. Silakan coba kembali.'
            );
        }
    }

    /**
     * Menampilkan jabatan yang sudah dihapus.
     */
    public function trash(Request $request): View
    {
        $search       = trim((string) $request->query('search', ''));
        $departmentId = $request->query('department_id');

        $positions = Position::onlyTrashed()
            ->with([
                'department:id,code,name,status',
            ])
            ->when(
                $search !== '',
                function (Builder $query) use ($search): void {
                    $query->where(
                        function (Builder $subQuery) use ($search): void {
                            $subQuery
                                ->where('code', 'ILIKE', "%{$search}%")
                                ->orWhere('name', 'ILIKE', "%{$search}%")
                                ->orWhere('description', 'ILIKE', "%{$search}%")
                                ->orWhereHas(
                                    'department',
                                    function (Builder $departmentQuery) use ($search): void {
                                        $departmentQuery
                                            ->where('code', 'ILIKE', "%{$search}%")
                                            ->orWhere('name', 'ILIKE', "%{$search}%");
                                    }
                                );
                        }
                    );
                }
            )
            ->when(
                filled($departmentId) && ctype_digit((string) $departmentId),
                fn(Builder $query): Builder => $query->where(
                    'department_id',
                    (int) $departmentId
                )
            )
            ->orderByDesc('deleted_at')
            ->paginate(10)
            ->withQueryString();

        $departments = Department::query()
            ->orderBy('name')
            ->get([
                'id',
                'code',
                'name',
                'status',
            ]);

        return view(
            'super-admin.positions.trash',
            compact(
                'positions',
                'departments',
                'search',
                'departmentId'
            )
        );
    }

    /**
     * Mengembalikan jabatan yang sudah dihapus.
     */
    public function restore(int $id): RedirectResponse
    {
        try {
            DB::transaction(
                function () use ($id): void {
                    $position = Position::onlyTrashed()
                        ->findOrFail($id);

                    $departmentExists = Department::query()
                        ->whereKey($position->department_id)
                        ->exists();

                    if (! $departmentExists) {
                        throw new RuntimeException(
                            'Departemen dari jabatan tersebut tidak tersedia.'
                        );
                    }

                    $position->restore();
                }
            );

            return redirect()
                ->route('super-admin.positions.index')
                ->with('success', 'Jabatan berhasil dikembalikan.');
        } catch (Throwable $exception) {
            report($exception);

            return back()->with(
                'error',
                $exception instanceof RuntimeException
                    ? $exception->getMessage()
                    : 'Jabatan gagal dikembalikan.'
            );
        }
    }

    /**
     * Menghapus jabatan secara permanen.
     */
    public function forceDelete(int $id): RedirectResponse
    {
        try {
            DB::transaction(
                function () use ($id): void {
                    $position = Position::onlyTrashed()
                        ->findOrFail($id);

                    /*
                     * Jika masih digunakan tabel lain, foreign key database
                     * akan menolak force delete dan error ditangani di bawah.
                     */
                    $position->forceDelete();
                }
            );

            return redirect()
                ->route('super-admin.positions.trash')
                ->with(
                    'success',
                    'Jabatan berhasil dihapus secara permanen.'
                );
        } catch (Throwable $exception) {
            report($exception);

            return back()->with(
                'error',
                $exception instanceof RuntimeException
                    ? $exception->getMessage()
                    : 'Jabatan gagal dihapus permanen karena masih digunakan oleh data lain.'
            );
        }
    }
}

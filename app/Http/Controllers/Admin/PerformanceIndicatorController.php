<?php

declare (strict_types = 1);

namespace App\Http\Controllers\Admin;

use App\Exports\PerformanceIndicatorsExport;
use App\Http\Controllers\Controller;
use App\Models\PerformanceIndicator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

class PerformanceIndicatorController extends Controller
{
    /**
     * Menampilkan daftar indikator kinerja.
     */
    public function index(Request $request): View
    {
        $filters = $this->validatedFilters($request);
        $perPage = (int) ($filters['per_page'] ?? 15);

        $performanceIndicators = $this->filteredQuery($filters)
            ->paginate($perPage)
            ->withQueryString();

        $statistics = [
            'total'               => PerformanceIndicator::query()->count(),
            'active'              => PerformanceIndicator::query()
                ->active()
                ->count(),
            'inactive'            => PerformanceIndicator::query()
                ->inactive()
                ->count(),
            'total_active_weight' => (float) PerformanceIndicator::query()
                ->active()
                ->sum('weight'),
        ];

        return view(
            'super-admin.performance-indicators.index',
            [
                'performanceIndicators' => $performanceIndicators,
                'statistics'            => $statistics,
                'filters'               => $filters,
                'statusOptions'         => PerformanceIndicator::statusOptions(),
                'directionOptions'      => PerformanceIndicator::targetDirectionOptions(),
            ]
        );
    }

    /**
     * Mengunduh seluruh data yang sesuai filter dalam format Excel (.xlsx).
     */
    public function exportExcel(Request $request): BinaryFileResponse
    {
        $filters = $this->validatedFilters($request);

        $performanceIndicators = $this->filteredQuery($filters)->get();

        return Excel::download(
            new PerformanceIndicatorsExport($performanceIndicators),
            $this->exportFilename('xlsx')
        );
    }

    /**
     * Mengunduh seluruh data yang sesuai filter dalam format PDF.
     */
    public function exportPdf(Request $request): Response
    {
        $filters = $this->validatedFilters($request);

        $performanceIndicators = $this->filteredQuery($filters)->get();

        $exportStatistics = [
            'total'        => $performanceIndicators->count(),
            'active'       => $performanceIndicators
                ->where('status', PerformanceIndicator::STATUS_ACTIVE)
                ->count(),
            'inactive'     => $performanceIndicators
                ->where('status', PerformanceIndicator::STATUS_INACTIVE)
                ->count(),
            'total_weight' => (float) $performanceIndicators->sum('weight'),
        ];

        $pdf = Pdf::loadView(
            'super-admin.performance-indicators.exports.pdf',
            [
                'performanceIndicators' => $performanceIndicators,
                'exportStatistics'      => $exportStatistics,
                'filters'               => $filters,
                'statusOptions'         => PerformanceIndicator::statusOptions(),
                'directionOptions'      => PerformanceIndicator::targetDirectionOptions(),
                'generatedAt'           => now(),
            ]
        )
            ->setPaper('a4', 'landscape')
            ->setOption('isRemoteEnabled', false);

        return $pdf->download(
            $this->exportFilename('pdf')
        );
    }

    /**
     * Menampilkan halaman tambah indikator.
     */
    public function create(): View
    {
        return view(
            'super-admin.performance-indicators.create',
            [
                'statusOptions'    => PerformanceIndicator::statusOptions(),
                'directionOptions' => PerformanceIndicator::targetDirectionOptions(),
                'defaultStatus'    => PerformanceIndicator::STATUS_ACTIVE,
                'defaultDirection' => PerformanceIndicator::DIRECTION_INCREASE,
            ]
        );
    }

    /**
     * Menyimpan indikator baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->normalizeRequest($request);

        $validated = $this->validateIndicator($request);

        try {
            $performanceIndicator = DB::transaction(
                function () use ($validated): PerformanceIndicator {
                    return PerformanceIndicator::query()->create(
                        $validated
                    );
                },
                3
            );

            return redirect()
                ->route(
                    'super-admin.performance-indicators.show',
                    $performanceIndicator
                )
                ->with(
                    'success',
                    "Indikator {$performanceIndicator->code} berhasil ditambahkan."
                );
        } catch (Throwable $exception) {
            report($exception);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Indikator gagal ditambahkan. Silakan periksa data dan coba kembali.'
                );
        }
    }

    /**
     * Menampilkan detail indikator.
     */
    public function show(
        PerformanceIndicator $performanceIndicator
    ): View {
        return view(
            'super-admin.performance-indicators.show',
            [
                'performanceIndicator' => $performanceIndicator,
            ]
        );
    }

    /**
     * Menampilkan halaman edit indikator.
     */
    public function edit(
        PerformanceIndicator $performanceIndicator
    ): View {
        return view(
            'super-admin.performance-indicators.edit',
            [
                'performanceIndicator' => $performanceIndicator,
                'statusOptions'        => PerformanceIndicator::statusOptions(),
                'directionOptions'     => PerformanceIndicator::targetDirectionOptions(),
            ]
        );
    }

    /**
     * Memperbarui indikator.
     */
    public function update(
        Request $request,
        PerformanceIndicator $performanceIndicator
    ): RedirectResponse {
        $this->normalizeRequest($request);

        $validated = $this->validateIndicator(
            request: $request,
            performanceIndicator: $performanceIndicator
        );

        try {
            DB::transaction(
                function () use (
                    $performanceIndicator,
                    $validated
                ): void {
                    $performanceIndicator->update(
                        $validated
                    );
                },
                3
            );

            $performanceIndicator->refresh();

            return redirect()
                ->route(
                    'super-admin.performance-indicators.show',
                    $performanceIndicator
                )
                ->with(
                    'success',
                    "Indikator {$performanceIndicator->code} berhasil diperbarui."
                );
        } catch (Throwable $exception) {
            report($exception);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Indikator gagal diperbarui. Silakan periksa data dan coba kembali.'
                );
        }
    }

    /**
     * Menghapus indikator secara permanen.
     */
    public function destroy(
        PerformanceIndicator $performanceIndicator
    ): RedirectResponse {
        $code = $performanceIndicator->code;

        try {
            DB::transaction(
                function () use ($performanceIndicator): void {
                    $performanceIndicator->delete();
                },
                3
            );

            return redirect()
                ->route('super-admin.performance-indicators.index')
                ->with(
                    'success',
                    "Indikator {$code} berhasil dihapus."
                );
        } catch (QueryException $exception) {
            report($exception);

            if ($this->isForeignKeyViolation($exception)) {
                return back()->with(
                    'error',
                    "Indikator {$code} tidak dapat dihapus karena masih digunakan oleh data lain."
                );
            }

            return back()->with(
                'error',
                'Indikator gagal dihapus karena terjadi kesalahan pada database.'
            );
        } catch (Throwable $exception) {
            report($exception);

            return back()->with(
                'error',
                'Indikator gagal dihapus. Silakan coba kembali.'
            );
        }
    }

    /**
     * Mengaktifkan atau menonaktifkan indikator.
     */
    public function toggleStatus(
        PerformanceIndicator $performanceIndicator
    ): RedirectResponse {
        try {
            DB::transaction(
                function () use ($performanceIndicator): void {
                    $performanceIndicator->toggleStatus();
                },
                3
            );

            $performanceIndicator->refresh();

            return back()->with(
                'success',
                "Status indikator {$performanceIndicator->code} berhasil diubah menjadi {$performanceIndicator->status_label}."
            );
        } catch (Throwable $exception) {
            report($exception);

            return back()->with(
                'error',
                'Status indikator gagal diubah.'
            );
        }
    }

    /**
     * Mengaktifkan indikator secara langsung.
     */
    public function activate(
        PerformanceIndicator $performanceIndicator
    ): RedirectResponse {
        try {
            $performanceIndicator->activate();

            return back()->with(
                'success',
                "Indikator {$performanceIndicator->code} berhasil diaktifkan."
            );
        } catch (Throwable $exception) {
            report($exception);

            return back()->with(
                'error',
                'Indikator gagal diaktifkan.'
            );
        }
    }

    /**
     * Menonaktifkan indikator secara langsung.
     */
    public function deactivate(
        PerformanceIndicator $performanceIndicator
    ): RedirectResponse {
        try {
            $performanceIndicator->deactivate();

            return back()->with(
                'success',
                "Indikator {$performanceIndicator->code} berhasil dinonaktifkan."
            );
        } catch (Throwable $exception) {
            report($exception);

            return back()->with(
                'error',
                'Indikator gagal dinonaktifkan.'
            );
        }
    }

    /**
     * Mengubah status beberapa indikator sekaligus.
     */
    public function bulkStatus(Request $request): RedirectResponse
    {
        $validated = $request->validate(
            [
                'ids'    => [
                    'required',
                    'array',
                    'min:1',
                ],
                'ids.*'  => [
                    'required',
                    'integer',
                    'distinct',
                    Rule::exists(
                        'performance_indicators',
                        'id'
                    ),
                ],
                'status' => [
                    'required',
                    Rule::in(
                        PerformanceIndicator::validStatuses()
                    ),
                ],
            ],
            [
                'ids.required'    => 'Pilih minimal satu indikator.',
                'ids.array'       => 'Data indikator tidak valid.',
                'ids.min'         => 'Pilih minimal satu indikator.',
                'ids.*.exists'    => 'Terdapat indikator yang tidak ditemukan.',
                'status.required' => 'Status wajib dipilih.',
                'status.in'       => 'Status yang dipilih tidak valid.',
            ]
        );

        try {
            $affectedRows = DB::transaction(
                function () use ($validated): int {
                    return PerformanceIndicator::query()
                        ->whereIn('id', $validated['ids'])
                        ->update([
                            'status'     => $validated['status'],
                            'updated_at' => now(),
                        ]);
                },
                3
            );

            $statusLabel = PerformanceIndicator::statusOptions()[
                $validated['status']
            ];

            return back()->with(
                'success',
                "{$affectedRows} indikator berhasil diubah menjadi {$statusLabel}."
            );
        } catch (Throwable $exception) {
            report($exception);

            return back()->with(
                'error',
                'Status indikator terpilih gagal diperbarui.'
            );
        }
    }

    /**
     * Menghapus beberapa indikator sekaligus.
     */
    public function bulkDestroy(Request $request): RedirectResponse
    {
        $validated = $request->validate(
            [
                'ids'   => [
                    'required',
                    'array',
                    'min:1',
                ],
                'ids.*' => [
                    'required',
                    'integer',
                    'distinct',
                    Rule::exists(
                        'performance_indicators',
                        'id'
                    ),
                ],
            ],
            [
                'ids.required' => 'Pilih minimal satu indikator.',
                'ids.array'    => 'Data indikator tidak valid.',
                'ids.min'      => 'Pilih minimal satu indikator.',
                'ids.*.exists' => 'Terdapat indikator yang tidak ditemukan.',
            ]
        );

        try {
            $deletedRows = DB::transaction(
                function () use ($validated): int {
                    return PerformanceIndicator::query()
                        ->whereIn('id', $validated['ids'])
                        ->delete();
                },
                3
            );

            return redirect()
                ->route('super-admin.performance-indicators.index')
                ->with(
                    'success',
                    "{$deletedRows} indikator berhasil dihapus."
                );
        } catch (QueryException $exception) {
            report($exception);

            if ($this->isForeignKeyViolation($exception)) {
                return back()->with(
                    'error',
                    'Sebagian indikator tidak dapat dihapus karena masih digunakan oleh data lain.'
                );
            }

            return back()->with(
                'error',
                'Indikator terpilih gagal dihapus karena kesalahan database.'
            );
        } catch (Throwable $exception) {
            report($exception);

            return back()->with(
                'error',
                'Indikator terpilih gagal dihapus.'
            );
        }
    }

    /**
     * Validasi filter daftar dan ekspor.
     */
    private function validatedFilters(Request $request): array
    {
        return $request->validate([
            'search'           => [
                'nullable',
                'string',
                'max:150',
            ],
            'status'           => [
                'nullable',
                Rule::in(
                    PerformanceIndicator::validStatuses()
                ),
            ],
            'target_direction' => [
                'nullable',
                Rule::in(
                    PerformanceIndicator::validTargetDirections()
                ),
            ],
            'sort_by'          => [
                'nullable',
                Rule::in([
                    'code',
                    'name',
                    'unit',
                    'weight',
                    'target_direction',
                    'status',
                    'created_at',
                    'updated_at',
                ]),
            ],
            'sort_direction'   => [
                'nullable',
                Rule::in([
                    'asc',
                    'desc',
                ]),
            ],
            'per_page'         => [
                'nullable',
                'integer',
                'in:10,15,25,50,100',
            ],
        ]);
    }

    /**
     * Menyusun query yang sama untuk index, Excel, dan PDF.
     */
    private function filteredQuery(array $filters): Builder
    {
        $sortBy        = $filters['sort_by'] ?? 'code';
        $sortDirection = $filters['sort_direction'] ?? 'asc';

        $query = PerformanceIndicator::query()
            ->search($filters['search'] ?? null)
            ->status($filters['status'] ?? null);

        if (! empty($filters['target_direction'])) {
            $query->direction(
                $filters['target_direction']
            );
        }

        return $query->orderBy(
            $sortBy,
            $sortDirection
        );
    }

    /**
     * Nama file ekspor yang konsisten dan aman.
     */
    private function exportFilename(string $extension): string
    {
        return sprintf(
            'performance-indicators-%s.%s',
            now()->format('Y-m-d-His'),
            $extension
        );
    }

    /**
     * Validasi data indikator.
     */
    private function validateIndicator(
        Request $request,
        ?PerformanceIndicator $performanceIndicator = null
    ): array {
        $uniqueCodeRule = Rule::unique(
            'performance_indicators',
            'code'
        );

        if ($performanceIndicator !== null) {
            $uniqueCodeRule->ignore(
                $performanceIndicator
            );
        }

        return $request->validate(
            [
                'code'             => [
                    'required',
                    'string',
                    'max:30',
                    'regex:/^[A-Z0-9._\-\/]+$/',
                    $uniqueCodeRule,
                ],
                'name'             => [
                    'required',
                    'string',
                    'max:150',
                ],
                'description'      => [
                    'nullable',
                    'string',
                ],
                'unit'             => [
                    'required',
                    'string',
                    'max:50',
                ],
                'weight'           => [
                    'required',
                    'numeric',
                    'min:0',
                    'max:100',
                    'decimal:0,2',
                ],
                'target_direction' => [
                    'required',
                    Rule::in(
                        PerformanceIndicator::validTargetDirections()
                    ),
                ],
                'status'           => [
                    'required',
                    Rule::in(
                        PerformanceIndicator::validStatuses()
                    ),
                ],
            ],
            [
                'code.required'             => 'Kode indikator wajib diisi.',
                'code.string'               => 'Kode indikator harus berupa teks.',
                'code.max'                  => 'Kode indikator maksimal 30 karakter.',
                'code.regex'                => 'Kode hanya boleh berisi huruf, angka, titik, garis bawah, garis miring, dan tanda hubung.',
                'code.unique'               => 'Kode indikator sudah digunakan.',

                'name.required'             => 'Nama indikator wajib diisi.',
                'name.string'               => 'Nama indikator harus berupa teks.',
                'name.max'                  => 'Nama indikator maksimal 150 karakter.',

                'description.string'        => 'Deskripsi indikator harus berupa teks.',

                'unit.required'             => 'Satuan indikator wajib diisi.',
                'unit.string'               => 'Satuan indikator harus berupa teks.',
                'unit.max'                  => 'Satuan indikator maksimal 50 karakter.',

                'weight.required'           => 'Bobot indikator wajib diisi.',
                'weight.numeric'            => 'Bobot indikator harus berupa angka.',
                'weight.min'                => 'Bobot indikator minimal 0.',
                'weight.max'                => 'Bobot indikator maksimal 100.',
                'weight.decimal'            => 'Bobot maksimal menggunakan dua angka desimal.',

                'target_direction.required' => 'Arah target wajib dipilih.',
                'target_direction.in'       => 'Arah target yang dipilih tidak valid.',

                'status.required'           => 'Status indikator wajib dipilih.',
                'status.in'                 => 'Status indikator yang dipilih tidak valid.',
            ],
            [
                'code'             => 'kode indikator',
                'name'             => 'nama indikator',
                'description'      => 'deskripsi',
                'unit'             => 'satuan',
                'weight'           => 'bobot',
                'target_direction' => 'arah target',
                'status'           => 'status',
            ]
        );
    }

    /**
     * Normalisasi input sebelum validasi.
     */
    private function normalizeRequest(Request $request): void
    {
        $description = trim(
            (string) $request->input('description', '')
        );

        $request->merge([
            'code'             => strtoupper(
                trim((string) $request->input('code', ''))
            ),
            'name'             => trim(
                (string) $request->input('name', '')
            ),
            'description'      => $description !== ''
                ? $description
                : null,
            'unit'             => trim(
                (string) $request->input('unit', '')
            ),
            'target_direction' => strtolower(
                trim(
                    (string) $request->input(
                        'target_direction',
                        ''
                    )
                )
            ),
            'status'           => strtolower(
                trim(
                    (string) $request->input(
                        'status',
                        ''
                    )
                )
            ),
        ]);
    }

    /**
     * SQLSTATE 23503 merupakan foreign key violation PostgreSQL.
     */
    private function isForeignKeyViolation(
        QueryException $exception
    ): bool {
        return isset($exception->errorInfo[0])
        && $exception->errorInfo[0] === '23503';
    }
}

<?php

declare (strict_types = 1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WorkSchedule;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Throwable;

class WorkScheduleController extends Controller
{
    /**
     * Menampilkan daftar jadwal kerja dengan pagination.
     */
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('search', ''));
        $status = $this->normalizeStatus($request->input('status'));

        $workSchedules = $this->filteredQuery($search, $status)
            ->orderBy('start_time')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('super-admin.work-schedules.index', [
            'workSchedules' => $workSchedules,
            'search'        => $search,
            'status'        => $status,
            'printMode'     => false,
        ]);
    }

    /**
     * Menampilkan seluruh isi tabel dalam mode cetak.
     *
     * Data tidak menggunakan pagination sehingga semua baris yang sesuai
     * filter akan dikirim ke halaman cetak.
     */
    public function printAll(Request $request): View
    {
        $search = trim((string) $request->input('search', ''));
        $status = $this->normalizeStatus($request->input('status'));

        $workSchedules = $this->filteredQuery($search, $status)
            ->orderBy('start_time')
            ->orderBy('name')
            ->get();

        return view('super-admin.work-schedules.index', [
            'workSchedules' => $workSchedules,
            'search'        => $search,
            'status'        => $status,
            'printMode'     => true,
            'printedAt'     => now(),
        ]);
    }

    /**
     * Menampilkan halaman tambah jadwal kerja.
     */
    public function create(): View
    {
        return view('super-admin.work-schedules.create', [
            'workSchedule' => new WorkSchedule(),
        ]);
    }

    /**
     * Menyimpan jadwal kerja baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateData($request);

        try {
            $validated['working_hours'] = $this->calculateWorkingHours(
                $validated['start_time'],
                $validated['end_time']
            );

            WorkSchedule::query()->create($validated);

            return redirect()
                ->route('super-admin.work-schedules.index')
                ->with('success', 'Jadwal kerja berhasil ditambahkan.');
        } catch (Throwable $exception) {
            report($exception);

            return back()
                ->withInput()
                ->with('error', 'Jadwal kerja gagal ditambahkan.');
        }
    }

    /**
     * Menampilkan detail jadwal kerja.
     */
    public function show(WorkSchedule $workSchedule): View
    {
        return view('super-admin.work-schedules.show', [
            'workSchedule' => $workSchedule,
        ]);
    }

    /**
     * Menampilkan halaman edit jadwal kerja.
     */
    public function edit(WorkSchedule $workSchedule): View
    {
        return view('super-admin.work-schedules.edit', [
            'workSchedule' => $workSchedule,
        ]);
    }

    /**
     * Memperbarui jadwal kerja.
     */
    public function update(
        Request $request,
        WorkSchedule $workSchedule
    ): RedirectResponse {
        $validated = $this->validateData($request, $workSchedule);

        try {
            $validated['working_hours'] = $this->calculateWorkingHours(
                $validated['start_time'],
                $validated['end_time']
            );

            $workSchedule->update($validated);

            return redirect()
                ->route('super-admin.work-schedules.index')
                ->with('success', 'Jadwal kerja berhasil diperbarui.');
        } catch (Throwable $exception) {
            report($exception);

            return back()
                ->withInput()
                ->with('error', 'Jadwal kerja gagal diperbarui.');
        }
    }

    /**
     * Menghapus jadwal kerja.
     */
    public function destroy(
        WorkSchedule $workSchedule
    ): RedirectResponse {
        try {
            $workSchedule->delete();

            return redirect()
                ->route('super-admin.work-schedules.index')
                ->with('success', 'Jadwal kerja berhasil dihapus.');
        } catch (Throwable $exception) {
            report($exception);

            return back()->with(
                'error',
                'Jadwal kerja gagal dihapus. Jadwal kemungkinan sedang digunakan oleh data lain.'
            );
        }
    }

    /**
     * Mengubah status jadwal kerja.
     */
    public function toggleStatus(
        WorkSchedule $workSchedule
    ): RedirectResponse {
        try {
            $newStatus = $workSchedule->status === 'active'
                ? 'inactive'
                : 'active';

            $workSchedule->update([
                'status' => $newStatus,
            ]);

            $statusLabel = $newStatus === 'active'
                ? 'aktif'
                : 'tidak aktif';

            return back()->with(
                'success',
                "Status jadwal kerja berhasil diubah menjadi {$statusLabel}."
            );
        } catch (Throwable $exception) {
            report($exception);

            return back()->with(
                'error',
                'Status jadwal kerja gagal diubah.'
            );
        }
    }

    /**
     * Query dasar yang digunakan index dan print.
     */
    private function filteredQuery(
        string $search,
        ?string $status
    ): Builder {
        return WorkSchedule::query()
            ->when(
                $search !== '',
                function (Builder $query) use ($search): void {
                    $searchTextPattern = '%' . mb_strtolower($search) . '%';
                    $searchTimePattern = '%' . $search . '%';

                    $query->where(
                        function (Builder $subQuery) use (
                            $searchTextPattern,
                            $searchTimePattern
                        ): void {
                            /*
                             * PostgreSQL tidak mengizinkan operator LIKE langsung
                             * pada kolom bertipe TIME. Karena itu jam dikonversi
                             * menjadi teks sebelum pencarian.
                             */
                            $subQuery
                                ->whereRaw(
                                    'LOWER(name) LIKE ?',
                                    [$searchTextPattern]
                                )
                                ->orWhereRaw(
                                    'CAST(start_time AS TEXT) LIKE ?',
                                    [$searchTimePattern]
                                )
                                ->orWhereRaw(
                                    'CAST(end_time AS TEXT) LIKE ?',
                                    [$searchTimePattern]
                                );
                        }
                    );
                }
            )
            ->when(
                $status !== null,
                function (Builder $query) use ($status): void {
                    $query->where('status', $status);
                }
            );
    }

    /**
     * Menormalkan status dari query string.
     */
    private function normalizeStatus(mixed $status): ?string
    {
        $normalizedStatus = is_string($status)
            ? trim($status)
            : '';

        return in_array(
            $normalizedStatus,
            ['active', 'inactive'],
            true
        )
            ? $normalizedStatus
            : null;
    }

    /**
     * Validasi data jadwal kerja.
     */
    private function validateData(
        Request $request,
        ?WorkSchedule $workSchedule = null
    ): array {
        return $request->validate(
            [
                'name'                   => [
                    'required',
                    'string',
                    'max:100',
                    Rule::unique('work_schedules', 'name')
                        ->ignore($workSchedule?->getKey()),
                ],

                'start_time'             => [
                    'required',
                    'date_format:H:i',
                ],

                'end_time'               => [
                    'required',
                    'date_format:H:i',
                    'different:start_time',
                ],

                'late_tolerance_minutes' => [
                    'required',
                    'integer',
                    'min:0',
                    'max:1440',
                ],

                'status'                 => [
                    'required',
                    Rule::in(['active', 'inactive']),
                ],
            ],
            [
                'name.required'                   => 'Nama jadwal kerja wajib diisi.',
                'name.string'                     => 'Nama jadwal kerja harus berupa teks.',
                'name.max'                        => 'Nama jadwal kerja maksimal 100 karakter.',
                'name.unique'                     => 'Nama jadwal kerja sudah digunakan.',

                'start_time.required'             => 'Jam masuk wajib diisi.',
                'start_time.date_format'          => 'Format jam masuk tidak valid.',

                'end_time.required'               => 'Jam pulang wajib diisi.',
                'end_time.date_format'            => 'Format jam pulang tidak valid.',
                'end_time.different'              => 'Jam pulang tidak boleh sama dengan jam masuk.',

                'late_tolerance_minutes.required' => 'Toleransi keterlambatan wajib diisi.',
                'late_tolerance_minutes.integer'  => 'Toleransi keterlambatan harus berupa angka.',
                'late_tolerance_minutes.min'      => 'Toleransi keterlambatan minimal 0 menit.',
                'late_tolerance_minutes.max'      => 'Toleransi keterlambatan maksimal 1.440 menit.',

                'status.required'                 => 'Status jadwal kerja wajib dipilih.',
                'status.in'                       => 'Status jadwal kerja tidak valid.',
            ]
        );
    }

    /**
     * Menghitung total jam kerja.
     *
     * Mendukung shift lintas hari:
     * 08:00 - 16:00 = 8 jam
     * 22:00 - 06:00 = 8 jam
     */
    private function calculateWorkingHours(
        string $startTime,
        string $endTime
    ): float {
        $start = Carbon::createFromFormat('H:i', $startTime);
        $end   = Carbon::createFromFormat('H:i', $endTime);

        if ($end->lessThanOrEqualTo($start)) {
            $end->addDay();
        }

        $totalMinutes = $start->diffInMinutes($end);

        return round((float) $totalMinutes / 60, 2);
    }
}

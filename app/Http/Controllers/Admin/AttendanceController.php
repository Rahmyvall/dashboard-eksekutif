<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAttendanceRequest;
use App\Http\Requests\UpdateAttendanceRequest;
use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function mine(Request $request): View
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:150'],
            'status' => ['nullable', 'string'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'per_page' => ['nullable', 'integer', 'in:10,25,50,100'],
        ]);

        $employee = $request->user()?->resolveEmployee();

        if (! $employee) {
            return view('super-admin.attendances.index', [
                'attendances' => Attendance::query()->whereRaw('1 = 0')->paginate(10),
                'employees' => collect(),
                'statuses' => Attendance::statuses(),
                'filters' => [
                    'search' => (string) ($validated['search'] ?? ''),
                    'employee_id' => '',
                    'status' => '',
                    'start_date' => (string) ($validated['start_date'] ?? ''),
                    'end_date' => (string) ($validated['end_date'] ?? ''),
                    'per_page' => (string) ($validated['per_page'] ?? '10'),
                ],
            ]);
        }

        $status = $this->normalizeStatus($validated['status'] ?? null);

        $statusOptions = $this->statusOptions();

        if (! in_array($status, $statusOptions, true)) {
            $status = '';
        }

        $attendances = Attendance::query()
            ->with(['employee.department', 'employee.position'])
            ->where('employee_id', $employee->id)
            ->when(
                filled($validated['search'] ?? null),
                function ($query) use ($validated): void {
                    $keyword = trim((string) $validated['search']);

                    $query->where(function ($subQuery) use ($keyword): void {
                        $subQuery
                            ->where('notes', 'like', "%{$keyword}%")
                            ->orWhereHas('employee', function ($employeeQuery) use ($keyword): void {
                                $employeeQuery
                                    ->where('full_name', 'like', "%{$keyword}%")
                                    ->orWhere('employee_number', 'like', "%{$keyword}%");
                            });
                    });
                }
            )
            ->when(
                $status !== '',
                fn($query) => $query->whereRaw($this->normalizedStatusSql() . ' = ?', [$status])
            )
            ->when(
                filled($validated['start_date'] ?? null) && filled($validated['end_date'] ?? null),
                fn($query) => $query->whereBetween('attendance_date', [$validated['start_date'], $validated['end_date']])
            )
            ->orderByDesc('attendance_date')
            ->orderByDesc('id')
            ->paginate((int) ($validated['per_page'] ?? 10))
            ->withQueryString();

        return view('super-admin.attendances.index', [
            'attendances' => $attendances,
            'employees' => collect([$employee]),
            'statuses' => $statusOptions,
            'filters' => [
                'search' => (string) ($validated['search'] ?? ''),
                'employee_id' => (string) $employee->id,
                'status' => $status,
                'start_date' => (string) ($validated['start_date'] ?? ''),
                'end_date' => (string) ($validated['end_date'] ?? ''),
                'per_page' => (string) ($validated['per_page'] ?? '10'),
            ],
        ]);
    }

    public function index(Request $request): View
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:150'],
            'employee_id' => ['nullable', 'integer', 'exists:employees,id'],
            'status' => ['nullable', 'string'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'per_page' => ['nullable', 'integer', 'in:10,25,50,100'],
        ]);

        $status = $this->normalizeStatus($validated['status'] ?? null);

        $statusOptions = $this->statusOptions();

        if (! in_array($status, $statusOptions, true)) {
            $status = '';
        }

        $attendances = Attendance::query()
            ->with(['employee.department', 'employee.position'])
            ->when(
                filled($validated['search'] ?? null),
                function ($query) use ($validated): void {
                    $keyword = trim((string) $validated['search']);

                    $query->where(function ($subQuery) use ($keyword): void {
                        $subQuery
                            ->where('notes', 'like', "%{$keyword}%")
                            ->orWhereHas('employee', function ($employeeQuery) use ($keyword): void {
                                $employeeQuery
                                    ->where('full_name', 'like', "%{$keyword}%")
                                    ->orWhere('employee_number', 'like', "%{$keyword}%");
                            });
                    });
                }
            )
            ->when(
                filled($validated['employee_id'] ?? null),
                fn($query) => $query->where('employee_id', (int) $validated['employee_id'])
            )
            ->when(
                $status !== '',
                fn($query) => $query->whereRaw($this->normalizedStatusSql() . ' = ?', [$status])
            )
            ->when(
                filled($validated['start_date'] ?? null) && filled($validated['end_date'] ?? null),
                fn($query) => $query->whereBetween('attendance_date', [$validated['start_date'], $validated['end_date']])
            )
            ->orderByDesc('attendance_date')
            ->orderByDesc('id')
            ->paginate((int) ($validated['per_page'] ?? 10))
            ->withQueryString();

        return view('super-admin.attendances.index', [
            'attendances' => $attendances,
            'employees' => Employee::query()->orderBy('full_name')->get(['id', 'full_name', 'employee_number']),
            'statuses' => $statusOptions,
            'filters' => [
                'search' => (string) ($validated['search'] ?? ''),
                'employee_id' => $validated['employee_id'] ?? '',
                'status' => $status,
                'start_date' => (string) ($validated['start_date'] ?? ''),
                'end_date' => (string) ($validated['end_date'] ?? ''),
                'per_page' => (string) ($validated['per_page'] ?? '10'),
            ],
        ]);
    }

    public function create(): View
    {
        return view('super-admin.attendances.create', [
            'attendance' => new Attendance(),
            'employees' => Employee::query()->active()->orderBy('full_name')->get(['id', 'full_name', 'employee_number']),
            'statuses' => Attendance::statuses(),
        ]);
    }

    public function store(StoreAttendanceRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $attendance = new Attendance($validated);

        if ((int) ($validated['work_duration_minutes'] ?? 0) <= 0) {
            $attendance->syncWorkDuration();
        }

        $attendance->work_duration_minutes = max(0, (int) ($attendance->work_duration_minutes ?? 0));
        $attendance->late_minutes = max(0, (int) ($validated['late_minutes'] ?? 0));
        $attendance->overtime_minutes = max(0, (int) ($validated['overtime_minutes'] ?? 0));
        $attendance->save();

        return redirect()
            ->route('super-admin.attendances.show', $attendance)
            ->with('success', 'Data kehadiran berhasil ditambahkan.');
    }

    public function show(Attendance $attendance): View
    {
        $attendance->load(['employee.department', 'employee.position']);

        return view('super-admin.attendances.show', [
            'attendance' => $attendance,
        ]);
    }

    public function edit(Attendance $attendance): View
    {
        return view('super-admin.attendances.edit', [
            'attendance' => $attendance,
            'employees' => Employee::query()->orderBy('full_name')->get(['id', 'full_name', 'employee_number']),
            'statuses' => Attendance::statuses(),
        ]);
    }

    public function update(UpdateAttendanceRequest $request, Attendance $attendance): RedirectResponse
    {
        $validated = $request->validated();

        $attendance->fill($validated);

        if ((int) ($validated['work_duration_minutes'] ?? 0) <= 0) {
            $attendance->syncWorkDuration();
        }

        $attendance->work_duration_minutes = max(0, (int) ($attendance->work_duration_minutes ?? 0));
        $attendance->late_minutes = max(0, (int) ($validated['late_minutes'] ?? 0));
        $attendance->overtime_minutes = max(0, (int) ($validated['overtime_minutes'] ?? 0));
        $attendance->save();

        return redirect()
            ->route('super-admin.attendances.show', $attendance)
            ->with('success', 'Data kehadiran berhasil diperbarui.');
    }

    public function destroy(Attendance $attendance): RedirectResponse
    {
        $attendance->delete();

        return redirect()
            ->route('super-admin.attendances.index')
            ->with('success', 'Data kehadiran berhasil dihapus.');
    }

    /**
     * @return array<int, string>
     */
    private function statusOptions(): array
    {
        $fromModel = collect(Attendance::statuses())
            ->map(fn($value): string => $this->normalizeStatus($value))
            ->filter()
            ->values();

        $fromDatabase = Attendance::query()
            ->selectRaw($this->normalizedStatusSql() . ' as normalized_status')
            ->whereNotNull('status')
            ->pluck('normalized_status')
            ->map(fn($value): string => $this->normalizeStatus($value))
            ->filter()
            ->values();

        return $fromModel
            ->merge($fromDatabase)
            ->unique()
            ->values()
            ->all();
    }

    private function normalizeStatus(mixed $value): string
    {
        return strtolower(trim((string) $value));
    }

    private function normalizedStatusSql(): string
    {
        return 'LOWER(TRIM(status))';
    }
}
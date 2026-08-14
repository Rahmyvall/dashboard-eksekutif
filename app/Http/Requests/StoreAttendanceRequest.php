<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Attendance;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'status' => strtolower(trim((string) $this->input('status', Attendance::STATUS_PRESENT))),
            'notes' => trim((string) $this->input('notes', '')),
            'check_in' => $this->filled('check_in') ? $this->input('check_in') : null,
            'check_out' => $this->filled('check_out') ? $this->input('check_out') : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'employee_id' => ['required', 'integer', 'exists:employees,id'],
            'attendance_date' => ['required', 'date'],
            'check_in' => ['nullable', 'date_format:H:i'],
            'check_out' => ['nullable', 'date_format:H:i', 'different:check_in'],
            'work_duration_minutes' => ['nullable', 'integer', 'min:0'],
            'late_minutes' => ['nullable', 'integer', 'min:0'],
            'overtime_minutes' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'string', Rule::in(Attendance::statuses())],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'employee_id.required' => 'Pegawai wajib dipilih.',
            'employee_id.exists' => 'Pegawai tidak ditemukan.',
            'attendance_date.required' => 'Tanggal kehadiran wajib diisi.',
            'check_in.date_format' => 'Format jam masuk harus HH:MM.',
            'check_out.date_format' => 'Format jam pulang harus HH:MM.',
            'check_out.different' => 'Jam pulang tidak boleh sama dengan jam masuk.',
            'status.in' => 'Status kehadiran tidak valid.',
        ];
    }
}

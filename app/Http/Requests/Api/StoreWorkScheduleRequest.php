<?php

declare (strict_types = 1);

namespace App\Http\Requests\Api\WorkSchedule;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreWorkScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'                   => [
                'required',
                'string',
                'max:100',
                Rule::unique('work_schedules', 'name'),
            ],
            'start_time'             => ['required', 'date_format:H:i'],
            'end_time'               => ['required', 'date_format:H:i', 'different:start_time'],
            'late_tolerance_minutes' => ['required', 'integer', 'min:0', 'max:1440'],
            'status'                 => ['required', Rule::in(['active', 'inactive'])],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'                   => 'Nama jadwal kerja wajib diisi.',
            'name.max'                        => 'Nama jadwal kerja maksimal 100 karakter.',
            'name.unique'                     => 'Nama jadwal kerja sudah digunakan.',
            'start_time.required'             => 'Jam masuk wajib diisi.',
            'start_time.date_format'          => 'Jam masuk harus menggunakan format HH:mm.',
            'end_time.required'               => 'Jam pulang wajib diisi.',
            'end_time.date_format'            => 'Jam pulang harus menggunakan format HH:mm.',
            'end_time.different'              => 'Jam pulang tidak boleh sama dengan jam masuk.',
            'late_tolerance_minutes.required' => 'Toleransi keterlambatan wajib diisi.',
            'late_tolerance_minutes.integer'  => 'Toleransi keterlambatan harus berupa angka.',
            'late_tolerance_minutes.min'      => 'Toleransi keterlambatan minimal 0 menit.',
            'late_tolerance_minutes.max'      => 'Toleransi keterlambatan maksimal 1.440 menit.',
            'status.required'                 => 'Status wajib dipilih.',
            'status.in'                       => 'Status hanya boleh active atau inactive.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name'       => trim((string) $this->input('name')),
            'start_time' => substr(trim((string) $this->input('start_time')), 0, 5),
            'end_time'   => substr(trim((string) $this->input('end_time')), 0, 5),
            'status'     => strtolower(trim((string) $this->input('status', 'active'))),
        ]);
    }
}

<?php

declare (strict_types = 1);

namespace App\Http\Requests\Api\WorkSchedule;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexWorkScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search'     => ['nullable', 'string', 'max:100'],
            'status'     => ['nullable', Rule::in(['active', 'inactive'])],
            'shift_type' => ['nullable', Rule::in(['same_day', 'overnight'])],
            'sort'       => [
                'nullable',
                Rule::in([
                    'id',
                    'name',
                    'start_time',
                    'end_time',
                    'late_tolerance_minutes',
                    'working_hours',
                    'status',
                    'created_at',
                    'updated_at',
                ]),
            ],
            'direction'  => ['nullable', Rule::in(['asc', 'desc'])],
            'per_page'   => ['nullable', 'integer', 'min:1', 'max:100'],
            'page'       => ['nullable', 'integer', 'min:1'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'search'     => trim((string) $this->input('search', '')),
            'status'     => $this->filled('status')
                ? strtolower(trim((string) $this->input('status')))
                : null,
            'shift_type' => $this->filled('shift_type')
                ? strtolower(trim((string) $this->input('shift_type')))
                : null,
            'sort'       => trim((string) $this->input('sort', 'start_time')),
            'direction'  => strtolower((string) $this->input('direction', 'asc')),
            'per_page'   => $this->input('per_page', 15),
        ]);
    }
}

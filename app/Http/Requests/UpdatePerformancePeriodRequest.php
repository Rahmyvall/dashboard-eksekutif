<?php

declare (strict_types = 1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePerformancePeriodRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => [
                'required',
                'string',
                'max:255',
            ],

            'start_date'  => [
                'required',
                'date_format:Y-m-d',
            ],

            'end_date'    => [
                'required',
                'date_format:Y-m-d',
                'after_or_equal:start_date',
            ],

            'period_type' => [
                'required',
                'string',
                'max:50',
                Rule::in([
                    'monthly',
                    'quarterly',
                    'semester',
                    'annual',
                ]),
            ],

            'status'      => [
                'required',
                'string',
                'max:30',
                Rule::in([
                    'draft',
                    'active',
                    'completed',
                    'inactive',
                ]),
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'name'        => 'nama periode',
            'start_date'  => 'tanggal mulai',
            'end_date'    => 'tanggal selesai',
            'period_type' => 'jenis periode',
            'status'      => 'status',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'           => 'Nama periode wajib diisi.',
            'name.max'                => 'Nama periode maksimal 255 karakter.',

            'start_date.required'     => 'Tanggal mulai wajib diisi.',
            'start_date.date_format'  =>
            'Format tanggal mulai harus YYYY-MM-DD.',

            'end_date.required'       => 'Tanggal selesai wajib diisi.',
            'end_date.date_format'    =>
            'Format tanggal selesai harus YYYY-MM-DD.',
            'end_date.after_or_equal' =>
            'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.',

            'period_type.required'    =>
            'Jenis periode wajib dipilih.',
            'period_type.in'          =>
            'Jenis periode yang dipilih tidak valid.',

            'status.required'         => 'Status wajib dipilih.',
            'status.in'               => 'Status yang dipilih tidak valid.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name'        => trim((string) $this->input('name', '')),
            'period_type' => strtolower(
                trim((string) $this->input('period_type', ''))
            ),
            'status'      => strtolower(
                trim((string) $this->input('status', ''))
            ),
        ]);
    }
}

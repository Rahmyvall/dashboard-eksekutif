<?php

declare (strict_types = 1);

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePerformancePeriodApiRequest extends FormRequest
{
    /**
     * Untuk pengujian Postman localhost.
     * Otorisasi utama dapat ditambahkan melalui middleware route.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
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
                Rule::in([
                    'draft',
                    'active',
                    'completed',
                    'inactive',
                ]),
            ],
        ];
    }

    /**
     * Normalisasi data sebelum proses validasi.
     */
    protected function prepareForValidation(): void
    {
        $payload = [];

        if ($this->has('name')) {
            $payload['name'] = trim(
                (string) $this->input('name')
            );
        }

        if ($this->has('start_date')) {
            $payload['start_date'] = trim(
                (string) $this->input('start_date')
            );
        }

        if ($this->has('end_date')) {
            $payload['end_date'] = trim(
                (string) $this->input('end_date')
            );
        }

        if ($this->has('period_type')) {
            $payload['period_type'] = strtolower(
                trim((string) $this->input('period_type'))
            );
        }

        if ($this->has('status')) {
            $payload['status'] = strtolower(
                trim((string) $this->input('status'))
            );
        }

        $this->merge($payload);
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required'           =>
            'Nama periode wajib diisi.',

            'name.string'             =>
            'Nama periode harus berupa teks.',

            'name.max'                =>
            'Nama periode maksimal 255 karakter.',

            'start_date.required'     =>
            'Tanggal mulai wajib diisi.',

            'start_date.date_format'  =>
            'Tanggal mulai harus menggunakan format Y-m-d.',

            'end_date.required'       =>
            'Tanggal selesai wajib diisi.',

            'end_date.date_format'    =>
            'Tanggal selesai harus menggunakan format Y-m-d.',

            'end_date.after_or_equal' =>
            'Tanggal selesai tidak boleh sebelum tanggal mulai.',

            'period_type.required'    =>
            'Jenis periode wajib dipilih.',

            'period_type.in'          =>
            'Jenis periode harus monthly, quarterly, semester, atau annual.',

            'status.required'         =>
            'Status periode wajib dipilih.',

            'status.in'               =>
            'Status harus draft, active, completed, atau inactive.',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name'        => 'nama periode',
            'start_date'  => 'tanggal mulai',
            'end_date'    => 'tanggal selesai',
            'period_type' => 'jenis periode',
            'status'      => 'status periode',
        ];
    }
}

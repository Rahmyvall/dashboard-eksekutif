<?php

declare (strict_types = 1);

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDepartmentRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna boleh melakukan request ini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Normalisasi payload sebelum validasi.
     */
    protected function prepareForValidation(): void
    {
        $description = $this->input('description');

        $this->merge([
            'code'        => strtoupper(
                trim((string) $this->input('code', ''))
            ),

            'name'        => trim(
                (string) $this->input('name', '')
            ),

            'description' => is_string($description)
                ? ($trimmed = trim($description)) !== ''
                ? $trimmed
                : null
                : $description,

            'status'      => strtolower(
                trim((string) $this->input('status', ''))
            ),
        ]);
    }

    /**
     * Aturan validasi penambahan department.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'code'        => [
                'bail',
                'required',
                'string',
                'max:30',
                'regex:/^[A-Z0-9_-]+$/',
                Rule::unique('departments', 'code'),
            ],

            'name'        => [
                'bail',
                'required',
                'string',
                'max:150',
            ],

            'description' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'status'      => [
                'bail',
                'required',
                Rule::in([
                    'active',
                    'inactive',
                ]),
            ],
        ];
    }

    /**
     * Pesan kesalahan validasi.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'code.required'      => 'Kode department wajib diisi.',
            'code.string'        => 'Kode department harus berupa teks.',
            'code.max'           => 'Kode department maksimal 30 karakter.',
            'code.regex'         => 'Kode department hanya boleh berisi huruf, angka, tanda hubung, dan garis bawah.',
            'code.unique'        => 'Kode department sudah digunakan.',

            'name.required'      => 'Nama department wajib diisi.',
            'name.string'        => 'Nama department harus berupa teks.',
            'name.max'           => 'Nama department maksimal 150 karakter.',

            'description.string' => 'Deskripsi department harus berupa teks.',
            'description.max'    => 'Deskripsi department maksimal 5.000 karakter.',

            'status.required'    => 'Status department wajib diisi.',
            'status.in'          => 'Status department harus active atau inactive.',
        ];
    }

    /**
     * Nama atribut yang lebih mudah dibaca.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'code'        => 'kode department',
            'name'        => 'nama department',
            'description' => 'deskripsi department',
            'status'      => 'status department',
        ];
    }
}

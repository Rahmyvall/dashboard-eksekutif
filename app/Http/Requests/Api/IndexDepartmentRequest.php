<?php

declare (strict_types = 1);

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexDepartmentRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna boleh melakukan request ini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Normalisasi query parameter sebelum validasi.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'search'         => $this->filled('search')
                ? trim((string) $this->input('search'))
                : null,

            'status'         => $this->filled('status')
                ? strtolower(trim((string) $this->input('status')))
                : null,

            'sort_by'        => $this->filled('sort_by')
                ? strtolower(trim((string) $this->input('sort_by')))
                : null,

            'sort_direction' => $this->filled('sort_direction')
                ? strtolower(trim((string) $this->input('sort_direction')))
                : null,
        ]);
    }

    /**
     * Aturan validasi query daftar department dan trash.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'search'         => [
                'nullable',
                'string',
                'max:150',
            ],

            'status'         => [
                'nullable',
                Rule::in([
                    'active',
                    'inactive',
                ]),
            ],

            'sort_by'        => [
                'nullable',
                Rule::in([
                    'id',
                    'code',
                    'name',
                    'status',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ]),
            ],

            'sort_direction' => [
                'nullable',
                Rule::in([
                    'asc',
                    'desc',
                ]),
            ],

            'per_page'       => [
                'nullable',
                'integer',
                'min:1',
                'max:100',
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
            'search.string'     => 'Kata kunci pencarian harus berupa teks.',
            'search.max'        => 'Kata kunci pencarian maksimal 150 karakter.',

            'status.in'         => 'Status harus bernilai active atau inactive.',

            'sort_by.in'        => 'Kolom pengurutan tidak valid.',

            'sort_direction.in' => 'Arah pengurutan harus asc atau desc.',

            'per_page.integer'  => 'Jumlah data per halaman harus berupa angka.',
            'per_page.min'      => 'Jumlah data per halaman minimal 1.',
            'per_page.max'      => 'Jumlah data per halaman maksimal 100.',
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
            'search'         => 'pencarian',
            'status'         => 'status',
            'sort_by'        => 'kolom pengurutan',
            'sort_direction' => 'arah pengurutan',
            'per_page'       => 'jumlah data per halaman',
        ];
    }
}

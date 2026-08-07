<?php

declare (strict_types = 1);

namespace App\Http\Requests\ServiceCategory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexServiceCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $data = [];

        if ($this->exists('search')) {
            $search = trim((string) $this->input('search'));

            $data['search'] = $search !== ''
                ? $search
                : null;
        }

        if ($this->exists('status')) {
            $status = strtolower(
                trim((string) $this->input('status'))
            );

            $data['status'] = $status !== ''
                ? $status
                : null;
        }

        if ($this->exists('sort_by')) {
            $data['sort_by'] = strtolower(
                trim((string) $this->input('sort_by'))
            );
        }

        if ($this->exists('sort_direction')) {
            $data['sort_direction'] = strtolower(
                trim((string) $this->input('sort_direction'))
            );
        }

        if ($data !== []) {
            $this->merge($data);
        }
    }

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
                Rule::in([
                    10,
                    15,
                    25,
                    50,
                    100,
                ]),
            ],

            'page'           => [
                'nullable',
                'integer',
                'min:1',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'search.string'     =>
            'Pencarian harus berupa teks.',

            'search.max'        =>
            'Pencarian maksimal 150 karakter.',

            'status.in'         =>
            'Status harus active atau inactive.',

            'sort_by.in'        =>
            'Kolom pengurutan tidak valid.',

            'sort_direction.in' =>
            'Arah pengurutan harus asc atau desc.',

            'per_page.integer'  =>
            'Jumlah data per halaman harus berupa angka.',

            'per_page.in'       =>
            'Per halaman hanya boleh 10, 15, 25, 50, atau 100.',

            'page.integer'      =>
            'Nomor halaman harus berupa angka.',

            'page.min'          =>
            'Nomor halaman minimal 1.',
        ];
    }
}

<?php

declare (strict_types = 1);

namespace App\Http\Requests\ServiceCategory;

use App\Models\ServiceCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreServiceCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $data = [];

        $name = trim((string) $this->input('name'));

        if ($this->exists('name')) {
            $data['name'] = $name;
        }

        $data['code'] = ServiceCategory::nextServiceCategoryCode($name);

        if ($this->exists('description')) {
            $description = trim(
                (string) $this->input('description')
            );

            $data['description'] = $description !== ''
                ? $description
                : null;
        }

        if ($this->exists('status')) {
            $data['status'] = strtolower(
                trim((string) $this->input('status'))
            );
        } else {
            $data['status'] = ServiceCategory::STATUS_ACTIVE;
        }

        $this->merge($data);
    }

    public function rules(): array
    {
        return [
            'code'        => [
                'nullable',
                'string',
                'max:30',
                'regex:/^[A-Z0-9._\-\/]+$/',

                Rule::unique(
                    'service_categories',
                    'code'
                ),
            ],

            'name'        => [
                'required',
                'string',
                'max:150',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'status'      => [
                'required',
                'string',
                'max:30',

                Rule::in([
                    ServiceCategory::STATUS_ACTIVE,
                    ServiceCategory::STATUS_INACTIVE,
                ]),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'code.string'        =>
            'Kode kategori layanan harus berupa teks.',

            'code.max'           =>
            'Kode kategori layanan maksimal 30 karakter.',

            'code.regex'         =>
            'Kode hanya boleh berisi huruf, angka, titik, garis bawah, garis miring, dan tanda hubung.',

            'code.unique'        =>
            'Kode kategori layanan sudah digunakan.',

            'name.required'      =>
            'Nama kategori layanan wajib diisi.',

            'name.string'        =>
            'Nama kategori layanan harus berupa teks.',

            'name.max'           =>
            'Nama kategori layanan maksimal 150 karakter.',

            'description.string' =>
            'Deskripsi kategori layanan harus berupa teks.',

            'status.required'    =>
            'Status kategori layanan wajib dipilih.',

            'status.in'          =>
            'Status hanya boleh active atau inactive.',
        ];
    }
}

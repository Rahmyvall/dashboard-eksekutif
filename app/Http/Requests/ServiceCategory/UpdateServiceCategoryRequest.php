<?php

declare (strict_types = 1);

namespace App\Http\Requests\ServiceCategory;

use App\Models\ServiceCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateServiceCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $data = [];
        $serviceCategory = $this->route('serviceCategory');
        $serviceCategoryId = $serviceCategory instanceof ServiceCategory
            ? $serviceCategory->getKey()
            : $serviceCategory;

        if ($this->exists('name')) {
            $name = trim((string) $this->input('name'));
            $data['code'] = ServiceCategory::nextServiceCategoryCode(
                $name,
                $serviceCategoryId !== null ? (int) $serviceCategoryId : null
            );
        }

        if ($this->exists('name')) {
            $data['name'] = trim((string) $this->input('name'));
        }

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
        }

        if ($data !== []) {
            $this->merge($data);
        }
    }

    public function rules(): array
    {
        $serviceCategory = $this->route(
            'serviceCategory'
        );

        $serviceCategoryId =
        $serviceCategory instanceof ServiceCategory
            ? $serviceCategory->getKey()
            : $serviceCategory;

        $requiredRule = $this->isMethod('PATCH')
            ? 'sometimes'
            : 'required';

        return [
            'code'        => [
                'nullable',
                'string',
                'max:30',
                'regex:/^[A-Z0-9._\-\/]+$/',

                Rule::unique(
                    'service_categories',
                    'code'
                )->ignore($serviceCategoryId),
            ],

            'name'        => [
                $requiredRule,
                'string',
                'max:150',
            ],

            'description' => [
                'sometimes',
                'nullable',
                'string',
            ],

            'status'      => [
                $requiredRule,
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
            'code.max'           =>
            'Kode kategori layanan maksimal 30 karakter.',

            'code.regex'         =>
            'Format kode kategori layanan tidak valid.',

            'code.unique'        =>
            'Kode kategori layanan sudah digunakan.',

            'name.required'      =>
            'Nama kategori layanan wajib diisi.',

            'name.max'           =>
            'Nama kategori layanan maksimal 150 karakter.',

            'description.string' =>
            'Deskripsi harus berupa teks.',

            'status.required'    =>
            'Status wajib dipilih.',

            'status.in'          =>
            'Status hanya boleh active atau inactive.',
        ];
    }
}

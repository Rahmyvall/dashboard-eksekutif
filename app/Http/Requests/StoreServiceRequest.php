<?php

declare (strict_types = 1);

namespace App\Http\Requests;

use App\Models\Service;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $data = [
            'name'   => trim((string) $this->input('name', '')),
            'unit'   => trim((string) $this->input('unit', 'service')),
            'status' => strtolower(trim((string) $this->input('status', Service::STATUS_ACTIVE))),
        ];

        if ($this->has('service_code')) {
            $data['service_code'] = strtoupper(trim((string) $this->input('service_code')));
        }

        if ($this->has('description')) {
            $data['description'] = $this->input('description') === null
                ? null
                : trim((string) $this->input('description'));
        }

        $this->merge($data);
    }

    public function rules(): array
    {
        return [
            'service_category_id'        => ['bail', 'required', 'integer', 'exists:service_categories,id'],
            'service_code'               => ['bail', 'nullable', 'string', 'max:50', Rule::unique(Service::class, 'service_code')],
            'name'                       => ['bail', 'required', 'string', 'max:150'],
            'description'                => ['nullable', 'string'],
            'base_price'                 => ['nullable', 'numeric', 'min:0'],
            'estimated_duration_minutes' => ['nullable', 'integer', 'min:0'],
            'unit'                       => ['nullable', 'string', 'max:50'],
            'status'                     => ['bail', 'required', 'string', Rule::in(Service::statuses())],
        ];
    }

    public function messages(): array
    {
        return [
            'service_category_id.required' => 'Kategori service wajib dipilih.',
            'service_category_id.exists'   => 'Kategori service tidak ditemukan.',
            'service_code.unique'          => 'Kode service sudah digunakan.',
            'name.required'                => 'Nama service wajib diisi.',
            'status.in'                    => 'Status hanya boleh active atau inactive.',
        ];
    }
}

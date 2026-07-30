<?php

declare (strict_types = 1);

namespace App\Http\Requests\Api;

use App\Models\Department;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UpdateDepartmentRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna boleh melakukan request ini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Normalisasi field yang benar-benar dikirim oleh client.
     */
    protected function prepareForValidation(): void
    {
        $data = [];

        if ($this->exists('code')) {
            $data['code'] = strtoupper(
                trim((string) $this->input('code'))
            );
        }

        if ($this->exists('name')) {
            $data['name'] = trim(
                (string) $this->input('name')
            );
        }

        if ($this->exists('description')) {
            $description = $this->input('description');

            $data['description'] = is_string($description)
                ? ($trimmed = trim($description)) !== ''
                ? $trimmed
                : null
                : $description;
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

    /**
     * Aturan validasi perubahan department.
     *
     * PUT   : code, name, dan status wajib dikirim.
     * PATCH : hanya field yang ingin diubah yang perlu dikirim.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $department = $this->route('department');

        if (! $department instanceof Department && is_numeric($department)) {
            $department = Department::query()->find((int) $department);
        }

        $uniqueCode = Rule::unique('departments', 'code');

        if ($department instanceof Department) {
            $uniqueCode->ignore($department);
        }

        $presenceRules = $this->isMethod('patch')
            ? ['sometimes', 'bail', 'required']
            : ['bail', 'required'];

        return [
            'code'        => [
                 ...$presenceRules,
                'string',
                'max:30',
                'regex:/^[A-Z0-9_-]+$/',
                $uniqueCode,
            ],

            'name'        => [
                 ...$presenceRules,
                'string',
                'max:150',
            ],

            'description' => [
                'sometimes',
                'nullable',
                'string',
                'max:5000',
            ],

            'status'      => [
                 ...$presenceRules,
                Rule::in([
                    'active',
                    'inactive',
                ]),
            ],
        ];
    }

    /**
     * Tolak PATCH tanpa field yang dapat diperbarui.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if (
                $this->isMethod('patch')
                && ! $this->hasAny([
                    'code',
                    'name',
                    'description',
                    'status',
                ])
            ) {
                $validator->errors()->add(
                    'request',
                    'Minimal satu field harus dikirim untuk memperbarui department.'
                );
            }
        });
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

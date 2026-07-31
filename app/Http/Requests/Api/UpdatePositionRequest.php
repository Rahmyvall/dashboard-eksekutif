<?php
namespace App\Http\Requests\Api;

use App\Models\Position;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePositionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'department_id' => $this->filled('department_id')
                ? (int) $this->input('department_id')
                : null,
            'code'          => $this->filled('code')
                ? strtoupper(trim((string) $this->input('code')))
                : null,
            'name'          => $this->filled('name')
                ? preg_replace('/\s+/', ' ', trim((string) $this->input('name')))
                : null,
            'level'         => $this->filled('level')
                ? (int) $this->input('level')
                : null,
            'description'   => $this->filled('description')
                ? trim((string) $this->input('description'))
                : null,
            'status'        => $this->filled('status')
                ? strtolower(trim((string) $this->input('status')))
                : null,
        ]);
    }

    public function rules(): array
    {
        $routePosition = $this->route('position');

        $positionId = $routePosition instanceof Position
            ? $routePosition->getKey()
            : $routePosition;

        return [
            'department_id' => [
                'required',
                'integer',
                Rule::exists('departments', 'id')->whereNull('deleted_at'),
            ],
            'code'          => [
                'required',
                'string',
                'max:30',
                'regex:/^[A-Z0-9][A-Z0-9_-]*$/',
                Rule::unique('positions', 'code')->ignore($positionId),
            ],
            'name'          => ['required', 'string', 'max:150'],
            'level'         => ['required', 'integer', 'min:1', 'max:65535'],
            'description'   => ['nullable', 'string'],
            'status'        => ['required', Rule::in(['active', 'inactive'])],
        ];
    }

    public function messages(): array
    {
        return [
            'department_id.required' => 'Departemen wajib dipilih.',
            'department_id.integer'  => 'Departemen tidak valid.',
            'department_id.exists'   => 'Departemen tidak ditemukan atau sudah dihapus.',
            'code.required'          => 'Kode jabatan wajib diisi.',
            'code.max'               => 'Kode jabatan maksimal 30 karakter.',
            'code.regex'             => 'Kode hanya boleh berisi huruf kapital, angka, tanda hubung, dan garis bawah.',
            'code.unique'            => 'Kode jabatan sudah digunakan oleh jabatan lain.',
            'name.required'          => 'Nama jabatan wajib diisi.',
            'name.max'               => 'Nama jabatan maksimal 150 karakter.',
            'level.required'         => 'Level jabatan wajib diisi.',
            'level.integer'          => 'Level harus berupa angka bulat.',
            'level.min'              => 'Level minimal 1.',
            'level.max'              => 'Level maksimal 65.535.',
            'description.string'     => 'Deskripsi harus berupa teks.',
            'status.required'        => 'Status wajib dipilih.',
            'status.in'              => 'Status harus active atau inactive.',
        ];
    }
}

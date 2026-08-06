<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePerformanceIndicatorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code'             => [
                'required',
                'string',
                'max:30',
                'unique:performance_indicators,code',
            ],

            'name'             => [
                'required',
                'string',
                'max:150',
            ],

            'description'      => [
                'nullable',
                'string',
            ],

            'unit'             => [
                'required',
                'string',
                'max:50',
            ],

            'weight'           => [
                'required',
                'numeric',
                'between:0,100',
            ],

            'target_direction' => [
                'required',
                'string',
                'max:30',
                'in:higher,lower,equal',
            ],

            'status'           => [
                'required',
                'string',
                'max:30',
                'in:active,inactive',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'code.required'             => 'Kode indikator wajib diisi.',
            'code.max'                  => 'Kode maksimal 30 karakter.',
            'code.unique'               => 'Kode indikator sudah digunakan.',

            'name.required'             => 'Nama indikator wajib diisi.',
            'name.max'                  => 'Nama indikator maksimal 150 karakter.',

            'unit.required'             => 'Satuan indikator wajib diisi.',
            'unit.max'                  => 'Satuan maksimal 50 karakter.',

            'weight.required'           => 'Bobot indikator wajib diisi.',
            'weight.numeric'            => 'Bobot indikator harus berupa angka.',
            'weight.between'            => 'Bobot indikator harus antara 0 sampai 100.',

            'target_direction.required' => 'Arah target wajib diisi.',
            'target_direction.in'       =>
            'Arah target hanya boleh higher, lower, atau equal.',

            'status.required'           => 'Status wajib diisi.',
            'status.in'                 =>
            'Status hanya boleh active atau inactive.',
        ];
    }
}

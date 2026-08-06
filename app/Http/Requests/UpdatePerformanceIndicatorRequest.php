<?php
namespace App\Http\Requests;

use App\Models\PerformanceIndicator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePerformanceIndicatorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $routeIndicator = $this->route('indicator');

        $indicatorId = $routeIndicator instanceof PerformanceIndicator
            ? $routeIndicator->getKey()
            : $routeIndicator;

        return [
            'code'             => [
                'sometimes',
                'required',
                'string',
                'max:30',
                Rule::unique('performance_indicators', 'code')
                    ->ignore($indicatorId),
            ],

            'name'             => [
                'sometimes',
                'required',
                'string',
                'max:150',
            ],

            'description'      => [
                'sometimes',
                'nullable',
                'string',
            ],

            'unit'             => [
                'sometimes',
                'required',
                'string',
                'max:50',
            ],

            'weight'           => [
                'sometimes',
                'required',
                'numeric',
                'between:0,100',
            ],

            'target_direction' => [
                'sometimes',
                'required',
                'string',
                'max:30',
                'in:higher,lower,equal',
            ],

            'status'           => [
                'sometimes',
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
            'code.required'       => 'Kode indikator wajib diisi.',
            'code.max'            => 'Kode maksimal 30 karakter.',
            'code.unique'         => 'Kode indikator sudah digunakan.',

            'name.required'       => 'Nama indikator wajib diisi.',
            'name.max'            => 'Nama indikator maksimal 150 karakter.',

            'unit.required'       => 'Satuan indikator wajib diisi.',
            'unit.max'            => 'Satuan maksimal 50 karakter.',

            'weight.numeric'      => 'Bobot harus berupa angka.',
            'weight.between'      => 'Bobot harus antara 0 sampai 100.',

            'target_direction.in' =>
            'Arah target hanya boleh higher, lower, atau equal.',

            'status.in'           =>
            'Status hanya boleh active atau inactive.',
        ];
    }
}

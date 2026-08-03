<?php

declare (strict_types = 1);

namespace App\Http\Requests\Api\WorkSchedule;

use App\Models\WorkSchedule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateWorkScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        /** @var WorkSchedule|null $workSchedule */
        $workSchedule = $this->route('workSchedule');

        return [
            'name'                   => [
                'sometimes',
                'required',
                'string',
                'max:100',
                Rule::unique('work_schedules', 'name')
                    ->ignore($workSchedule?->getKey()),
            ],
            'start_time'             => ['sometimes', 'required', 'date_format:H:i'],
            'end_time'               => ['sometimes', 'required', 'date_format:H:i'],
            'late_tolerance_minutes' => [
                'sometimes',
                'required',
                'integer',
                'min:0',
                'max:1440',
            ],
            'status'                 => [
                'sometimes',
                'required',
                Rule::in(['active', 'inactive']),
            ],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            /** @var WorkSchedule|null $workSchedule */
            $workSchedule = $this->route('workSchedule');

            $startTime = (string) $this->input(
                'start_time',
                substr((string) $workSchedule?->start_time, 0, 5)
            );

            $endTime = (string) $this->input(
                'end_time',
                substr((string) $workSchedule?->end_time, 0, 5)
            );

            if ($startTime !== '' && $endTime !== '' && $startTime === $endTime) {
                $validator->errors()->add(
                    'end_time',
                    'Jam pulang tidak boleh sama dengan jam masuk.'
                );
            }
        });
    }

    public function messages(): array
    {
        return (new StoreWorkScheduleRequest())->messages();
    }

    protected function prepareForValidation(): void
    {
        $data = [];

        if ($this->exists('name')) {
            $data['name'] = trim((string) $this->input('name'));
        }

        if ($this->exists('start_time')) {
            $data['start_time'] = substr(trim((string) $this->input('start_time')), 0, 5);
        }

        if ($this->exists('end_time')) {
            $data['end_time'] = substr(trim((string) $this->input('end_time')), 0, 5);
        }

        if ($this->exists('status')) {
            $data['status'] = strtolower(trim((string) $this->input('status')));
        }

        $this->merge($data);
    }
}

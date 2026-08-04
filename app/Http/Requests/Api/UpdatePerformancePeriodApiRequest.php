<?php

declare (strict_types = 1);

namespace App\Http\Requests\Api;

use App\Models\PerformancePeriod;
use DateTimeImmutable;
use DateTimeInterface;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UpdatePerformancePeriodApiRequest extends FormRequest
{
    /**
     * Untuk pengujian Postman localhost.
     * Otorisasi utama dapat ditambahkan melalui middleware route.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * PUT mewajibkan seluruh field.
     * PATCH hanya memvalidasi field yang dikirim.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $presenceRule = $this->isMethod('PUT')
            ? 'required'
            : 'sometimes';

        return [
            'name'        => [
                $presenceRule,
                'required',
                'string',
                'max:255',
            ],

            'start_date'  => [
                $presenceRule,
                'required',
                'date_format:Y-m-d',
            ],

            'end_date'    => [
                $presenceRule,
                'required',
                'date_format:Y-m-d',
            ],

            'period_type' => [
                $presenceRule,
                'required',
                'string',
                Rule::in([
                    'monthly',
                    'quarterly',
                    'semester',
                    'annual',
                ]),
            ],

            'status'      => [
                $presenceRule,
                'required',
                'string',
                Rule::in([
                    'draft',
                    'active',
                    'completed',
                    'inactive',
                ]),
            ],
        ];
    }

    /**
     * Memvalidasi kombinasi tanggal lama dan tanggal baru.
     *
     * Method ini tetap bekerja ketika PATCH hanya mengirim
     * start_date atau hanya mengirim end_date.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(
            function (Validator $validator): void {
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                $performancePeriod = $this->route(
                    'performancePeriod'
                );

                if (
                    ! $performancePeriod instanceof PerformancePeriod
                ) {
                    return;
                }

                $startDate = $this->resolveDateValue(
                    'start_date',
                    $performancePeriod->start_date
                );

                $endDate = $this->resolveDateValue(
                    'end_date',
                    $performancePeriod->end_date
                );

                if ($startDate === null || $endDate === null) {
                    return;
                }

                $start = DateTimeImmutable::createFromFormat(
                    '!Y-m-d',
                    $startDate
                );

                $end = DateTimeImmutable::createFromFormat(
                    '!Y-m-d',
                    $endDate
                );

                if (
                    $start !== false &&
                    $end !== false &&
                    $end < $start
                ) {
                    $validator->errors()->add(
                        'end_date',
                        'Tanggal selesai tidak boleh sebelum tanggal mulai.'
                    );
                }
            }
        );
    }

    /**
     * Normalisasi data sebelum proses validasi.
     */
    protected function prepareForValidation(): void
    {
        $payload = [];

        foreach (
            [
                'name',
                'start_date',
                'end_date',
            ] as $field
        ) {
            if ($this->has($field)) {
                $payload[$field] = trim(
                    (string) $this->input($field)
                );
            }
        }

        foreach (
            [
                'period_type',
                'status',
            ] as $field
        ) {
            if ($this->has($field)) {
                $payload[$field] = strtolower(
                    trim((string) $this->input($field))
                );
            }
        }

        $this->merge($payload);
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required'          =>
            'Nama periode wajib diisi.',

            'name.string'            =>
            'Nama periode harus berupa teks.',

            'name.max'               =>
            'Nama periode maksimal 255 karakter.',

            'start_date.required'    =>
            'Tanggal mulai wajib diisi.',

            'start_date.date_format' =>
            'Tanggal mulai harus menggunakan format Y-m-d.',

            'end_date.required'      =>
            'Tanggal selesai wajib diisi.',

            'end_date.date_format'   =>
            'Tanggal selesai harus menggunakan format Y-m-d.',

            'period_type.required'   =>
            'Jenis periode wajib dipilih.',

            'period_type.in'         =>
            'Jenis periode harus monthly, quarterly, semester, atau annual.',

            'status.required'        =>
            'Status periode wajib dipilih.',

            'status.in'              =>
            'Status harus draft, active, completed, atau inactive.',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name'        => 'nama periode',
            'start_date'  => 'tanggal mulai',
            'end_date'    => 'tanggal selesai',
            'period_type' => 'jenis periode',
            'status'      => 'status periode',
        ];
    }

    /**
     * Mengambil tanggal dari request atau data lama pada database.
     */
    private function resolveDateValue(
        string $field,
        mixed $currentValue
    ): ?string {
        if ($this->has($field)) {
            $value = trim(
                (string) $this->input($field)
            );

            return $value !== ''
                ? $value
                : null;
        }

        if ($currentValue instanceof DateTimeInterface) {
            return $currentValue->format('Y-m-d');
        }

        if (is_string($currentValue)) {
            $value = trim($currentValue);

            return $value !== ''
                ? substr($value, 0, 10)
                : null;
        }

        return null;
    }
}

<?php
namespace App\Http\Requests\WorkSchedule;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreWorkScheduleRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna diizinkan melakukan request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi data jadwal kerja baru.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name'                   => [
                'required',
                'string',
                'max:100',
            ],

            'start_time'             => [
                'required',
                'date_format:H:i',
            ],

            'end_time'               => [
                'required',
                'date_format:H:i',
                'different:start_time',
            ],

            'late_tolerance_minutes' => [
                'sometimes',
                'integer',
                'min:0',
                'max:1440',
            ],

            'working_hours'          => [
                'sometimes',
                'numeric',
                'min:0',
                'max:999.99',
                'decimal:0,2',
            ],

            'status'                 => [
                'sometimes',
                'string',
                'max:30',
                Rule::in(['active', 'inactive']),
            ],
        ];
    }

    /**
     * Pesan validasi khusus.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required'                  => 'Nama jadwal kerja wajib diisi.',
            'name.string'                    => 'Nama jadwal kerja harus berupa teks.',
            'name.max'                       => 'Nama jadwal kerja maksimal 100 karakter.',

            'start_time.required'            => 'Jam mulai kerja wajib diisi.',
            'start_time.date_format'         => 'Format jam mulai harus HH:MM, misalnya 08:00.',

            'end_time.required'              => 'Jam selesai kerja wajib diisi.',
            'end_time.date_format'           => 'Format jam selesai harus HH:MM, misalnya 17:00.',
            'end_time.different'             => 'Jam selesai tidak boleh sama dengan jam mulai.',

            'late_tolerance_minutes.integer' => 'Toleransi keterlambatan harus berupa angka bulat.',
            'late_tolerance_minutes.min'     => 'Toleransi keterlambatan minimal 0 menit.',
            'late_tolerance_minutes.max'     => 'Toleransi keterlambatan maksimal 1440 menit.',

            'working_hours.numeric'          => 'Jumlah jam kerja harus berupa angka.',
            'working_hours.min'              => 'Jumlah jam kerja tidak boleh kurang dari 0.',
            'working_hours.max'              => 'Jumlah jam kerja maksimal 999,99 jam.',
            'working_hours.decimal'          => 'Jumlah jam kerja maksimal memiliki 2 angka desimal.',

            'status.in'                      => 'Status hanya boleh berisi active atau inactive.',
            'status.max'                     => 'Status maksimal 30 karakter.',
        ];
    }

    /**
     * Nama atribut yang ditampilkan dalam pesan validasi.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name'                   => 'nama jadwal kerja',
            'start_time'             => 'jam mulai',
            'end_time'               => 'jam selesai',
            'late_tolerance_minutes' => 'toleransi keterlambatan',
            'working_hours'          => 'jumlah jam kerja',
            'status'                 => 'status',
        ];
    }
}

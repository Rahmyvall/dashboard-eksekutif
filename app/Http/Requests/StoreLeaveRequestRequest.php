<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeaveRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $payload = [
            'leave_type' => strtolower(trim((string) $this->input('leave_type', ''))),
            'reason' => trim((string) $this->input('reason', '')),
        ];

        if ($this->has('attachment_path') && $this->input('attachment_path') === '') {
            $payload['attachment_path'] = null;
        }

        $this->merge($payload);
    }

    public function rules(): array
    {
        return [
            'employee_id' => ['nullable', 'integer', 'exists:employees,id'],
            'leave_type' => ['required', 'string', 'max:50'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'reason' => ['required', 'string'],
            'attachment' => ['nullable', 'file', 'max:5120', 'mimes:jpg,jpeg,png,pdf'],
        ];
    }

    public function messages(): array
    {
        return [
            'employee_id.exists' => 'Pegawai tidak ditemukan.',
            'leave_type.required' => 'Jenis cuti wajib diisi.',
            'start_date.required' => 'Tanggal mulai wajib diisi.',
            'end_date.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai.',
            'reason.required' => 'Alasan pengajuan cuti wajib diisi.',
            'attachment.max' => 'Ukuran lampiran maksimal 5 MB.',
            'attachment.mimes' => 'Lampiran harus berupa file JPG, JPEG, PNG, atau PDF.',
        ];
    }
}

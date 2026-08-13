<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $payload = [
            'category' => strtolower(trim((string) $this->input('category', ''))),
            'description' => trim((string) $this->input('description', '')),
        ];

        if ($this->has('attachment_path') && $this->input('attachment_path') === '') {
            $payload['attachment_path'] = null;
        }

        $this->merge($payload);
    }

    public function rules(): array
    {
        return [
            'service_order_id' => ['nullable', 'integer', 'exists:service_orders,id'],
            'expense_date' => ['required', 'date'],
            'category' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string'],
            'amount' => ['required', 'numeric', 'gt:0'],
            'attachment' => ['nullable', 'file', 'max:5120', 'mimes:jpg,jpeg,png,pdf'],
        ];
    }

    public function messages(): array
    {
        return [
            'service_order_id.exists' => 'Service order tidak ditemukan.',
            'expense_date.required' => 'Tanggal pengeluaran wajib diisi.',
            'category.required' => 'Kategori pengeluaran wajib diisi.',
            'amount.gt' => 'Nominal pengeluaran harus lebih besar dari 0.',
            'attachment.max' => 'Ukuran lampiran maksimal 5 MB.',
            'attachment.mimes' => 'Lampiran harus berupa file JPG, JPEG, PNG, atau PDF.',
        ];
    }
}

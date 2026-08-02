<?php

declare (strict_types = 1);

namespace App\Http\Requests;

use App\Models\Customer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCustomerRequest extends FormRequest
{
    /**
     * Menentukan apakah pengguna memiliki izin melakukan request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Normalisasi data sebelum proses validasi.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'customer_code' => strtoupper(
                trim((string) $this->input('customer_code', ''))
            ),

            'customer_type' => strtolower(
                trim((string) $this->input('customer_type', ''))
            ),

            'name'          => trim(
                (string) $this->input('name', '')
            ),

            'company_name'  => $this->normalizeNullableString(
                $this->input('company_name')
            ),

            'phone'         => $this->normalizeNullableString(
                $this->input('phone')
            ),

            'email'         => $this->normalizeEmail(
                $this->input('email')
            ),

            'address'       => $this->normalizeNullableString(
                $this->input('address')
            ),

            'tax_number'    => $this->normalizeNullableString(
                $this->input('tax_number')
            ),

            'status'        => strtolower(
                trim((string) $this->input('status', ''))
            ),
        ]);
    }

    /**
     * Aturan validasi perubahan customer.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $customer = $this->route('customer');

        $customerId = $customer instanceof Customer
            ? $customer->getKey()
            : $customer;

        return [
            'customer_code' => [
                'bail',
                'required',
                'string',
                'max:50',
                Rule::unique(Customer::class, 'customer_code')
                    ->ignore($customerId),
            ],

            'customer_type' => [
                'bail',
                'required',
                'string',
                'max:30',
                Rule::in(Customer::customerTypes()),
            ],

            'name'          => [
                'bail',
                'required',
                'string',
                'max:150',
            ],

            'company_name'  => [
                'bail',
                Rule::requiredIf(
                    fn(): bool => $this->input('customer_type')
                    === Customer::TYPE_COMPANY
                ),
                'nullable',
                'string',
                'max:150',
            ],

            'phone'         => [
                'bail',
                'nullable',
                'string',
                'max:30',
                'regex:/^[0-9+\-\s().]+$/',
            ],

            'email'         => [
                'bail',
                'nullable',
                'string',
                'email:rfc',
                'max:150',
            ],

            'address'       => [
                'bail',
                'nullable',
                'string',
            ],

            'tax_number'    => [
                'bail',
                'nullable',
                'string',
                'max:100',
            ],

            'status'        => [
                'bail',
                'required',
                'string',
                'max:30',
                Rule::in(Customer::statuses()),
            ],
        ];
    }

    /**
     * Pesan validasi dalam Bahasa Indonesia.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'customer_code.required' => 'Kode customer wajib diisi.',
            'customer_code.string'   => 'Kode customer harus berupa teks.',
            'customer_code.max'      => 'Kode customer maksimal 50 karakter.',
            'customer_code.unique'   => 'Kode customer sudah digunakan oleh customer lain.',

            'customer_type.required' => 'Jenis customer wajib dipilih.',
            'customer_type.string'   => 'Jenis customer tidak valid.',
            'customer_type.max'      => 'Jenis customer maksimal 30 karakter.',
            'customer_type.in'       => 'Jenis customer yang dipilih tidak valid.',

            'name.required'          => 'Nama customer wajib diisi.',
            'name.string'            => 'Nama customer harus berupa teks.',
            'name.max'               => 'Nama customer maksimal 150 karakter.',

            'company_name.required'  => 'Nama perusahaan wajib diisi untuk customer perusahaan.',
            'company_name.string'    => 'Nama perusahaan harus berupa teks.',
            'company_name.max'       => 'Nama perusahaan maksimal 150 karakter.',

            'phone.string'           => 'Nomor telepon harus berupa teks.',
            'phone.max'              => 'Nomor telepon maksimal 30 karakter.',
            'phone.regex'            => 'Format nomor telepon tidak valid.',

            'email.string'           => 'Email harus berupa teks.',
            'email.email'            => 'Format email tidak valid.',
            'email.max'              => 'Email maksimal 150 karakter.',

            'address.string'         => 'Alamat harus berupa teks.',

            'tax_number.string'      => 'Nomor pajak harus berupa teks.',
            'tax_number.max'         => 'Nomor pajak maksimal 100 karakter.',

            'status.required'        => 'Status customer wajib dipilih.',
            'status.string'          => 'Status customer tidak valid.',
            'status.max'             => 'Status customer maksimal 30 karakter.',
            'status.in'              => 'Status customer yang dipilih tidak valid.',
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
            'customer_code' => 'kode customer',
            'customer_type' => 'jenis customer',
            'name'          => 'nama customer',
            'company_name'  => 'nama perusahaan',
            'phone'         => 'nomor telepon',
            'email'         => 'email',
            'address'       => 'alamat',
            'tax_number'    => 'nomor pajak',
            'status'        => 'status customer',
        ];
    }

    /**
     * Mengubah string kosong menjadi null.
     */
    private function normalizeNullableString(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $normalizedValue = trim((string) $value);

        return $normalizedValue !== ''
            ? $normalizedValue
            : null;
    }

    /**
     * Menormalkan email menjadi huruf kecil.
     */
    private function normalizeEmail(mixed $value): ?string
    {
        $normalizedValue = $this->normalizeNullableString($value);

        return $normalizedValue !== null
            ? strtolower($normalizedValue)
            : null;
    }
}

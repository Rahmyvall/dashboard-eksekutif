<?php
namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Authorization check
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules
     */
    public function rules(): array
    {
        return [
            'email'       => [
                'required',
                'email',
            ],

            'password'    => [
                'required',
                'string',
            ],

            'device_name' => [
                'nullable',
                'string',
            ],
        ];
    }

    /**
     * Custom messages
     */
    public function messages(): array
    {
        return [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',

            'password.required' => 'Password wajib diisi.',
        ];
    }
}

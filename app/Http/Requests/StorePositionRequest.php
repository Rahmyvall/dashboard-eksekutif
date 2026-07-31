<?php
namespace App\Http\Requests;

use App\Models\Position;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePositionRequest extends FormRequest
{
    /**
     * Hentikan validasi setelah kegagalan pertama.
     */
    protected $stopOnFirstFailure = true;

    /**
     * Menentukan apakah pengguna boleh melakukan request.
     */
    public function authorize(): bool
    {
        /*
         * Route posisi sebaiknya sudah menggunakan middleware auth.
         *
         * Untuk sementara, seluruh pengguna yang sudah login diperbolehkan.
         * Nanti dapat diganti menggunakan Policy atau pengecekan role.
         */
        return $this->user() !== null;
    }

    /**
     * Membersihkan dan menormalkan input sebelum divalidasi.
     */
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
                ? preg_replace(
                '/\s+/',
                ' ',
                trim((string) $this->input('name'))
            )
                : null,

            'level'         => $this->filled('level')
                ? (int) $this->input('level')
                : null,

            'description'   => $this->filled('description')
                ? trim((string) $this->input('description'))
                : null,

            'status'        => $this->filled('status')
                ? strtolower(trim((string) $this->input('status')))
                : Position::STATUS_ACTIVE,
        ]);
    }

    /**
     * Aturan validasi tambah posisi.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'department_id' => [
                'bail',
                'required',
                'integer',
                Rule::exists('departments', 'id')
                    ->whereNull('deleted_at'),
            ],

            'code'          => [
                'bail',
                'required',
                'string',
                'max:30',
                'regex:/^[A-Z0-9][A-Z0-9_-]*$/',
                Rule::unique('positions', 'code'),
            ],

            'name'          => [
                'bail',
                'required',
                'string',
                'max:150',
            ],

            'level'         => [
                'bail',
                'required',
                'integer',
                'min:1',
                'max:65535',
            ],

            'description'   => [
                'nullable',
                'string',
            ],

            'status'        => [
                'bail',
                'required',
                'string',
                'max:30',
                Rule::in(Position::STATUSES),
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
            'department_id.required' => 'Departemen wajib dipilih.',
            'department_id.integer'  => 'Departemen yang dipilih tidak valid.',
            'department_id.exists'   => 'Departemen yang dipilih tidak ditemukan atau sudah dihapus.',

            'code.required'          => 'Kode jabatan wajib diisi.',
            'code.string'            => 'Kode jabatan harus berupa teks.',
            'code.max'               => 'Kode jabatan maksimal 30 karakter.',
            'code.regex'             => 'Kode jabatan hanya boleh berisi huruf kapital, angka, tanda hubung, dan garis bawah.',
            'code.unique'            => 'Kode jabatan sudah digunakan.',

            'name.required'          => 'Nama jabatan wajib diisi.',
            'name.string'            => 'Nama jabatan harus berupa teks.',
            'name.max'               => 'Nama jabatan maksimal 150 karakter.',

            'level.required'         => 'Level jabatan wajib diisi.',
            'level.integer'          => 'Level jabatan harus berupa angka bulat.',
            'level.min'              => 'Level jabatan minimal 1.',
            'level.max'              => 'Level jabatan maksimal 65.535.',

            'description.string'     => 'Deskripsi jabatan harus berupa teks.',

            'status.required'        => 'Status jabatan wajib dipilih.',
            'status.string'          => 'Status jabatan tidak valid.',
            'status.max'             => 'Status jabatan maksimal 30 karakter.',
            'status.in'              => 'Status jabatan harus aktif atau tidak aktif.',
        ];
    }

    /**
     * Nama atribut yang tampil pada pesan validasi.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'department_id' => 'departemen',
            'code'          => 'kode jabatan',
            'name'          => 'nama jabatan',
            'level'         => 'level jabatan',
            'description'   => 'deskripsi jabatan',
            'status'        => 'status jabatan',
        ];
    }
}

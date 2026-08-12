<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\EmployeeActivity;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Illuminate\Validation\Rule;

class StoreEmployeeActivityRequest extends FormRequest
{
	public function authorize(): bool
	{
		return true;
	}

	protected function prepareForValidation(): void
	{
		$this->merge([
			'employee_id'       => $this->normalizeNullableInteger($this->input('employee_id')),
			'service_order_id'  => $this->normalizeNullableInteger($this->input('service_order_id')),
			'activity_date'     => $this->normalizeNullableString($this->input('activity_date')),
			'activity_name'     => trim((string) $this->input('activity_name', '')),
			'description'       => $this->normalizeNullableString($this->input('description')),
			'quantity'          => $this->input('quantity', EmployeeActivity::DEFAULT_QUANTITY),
			'unit'              => $this->normalizeNullableString($this->input('unit')),
			'start_time'        => $this->normalizeNullableTime($this->input('start_time')),
			'end_time'          => $this->normalizeNullableTime($this->input('end_time')),
			'duration_minutes'  => $this->normalizeNullableInteger($this->input('duration_minutes')),
			'status'            => strtolower(trim((string) $this->input('status', EmployeeActivity::STATUS_SUBMITTED))),
			'verified_by'       => $this->normalizeNullableInteger($this->input('verified_by')),
			'verified_at'       => $this->normalizeNullableString($this->input('verified_at')),
		]);
	}

	public function rules(): array
	{
		return [
			'employee_id'      => ['bail', 'required', 'integer', 'exists:employees,id'],
			'service_order_id' => ['bail', 'nullable', 'integer', 'exists:service_orders,id'],
			'activity_date'    => ['bail', 'required', 'date'],
			'activity_name'    => ['bail', 'required', 'string', 'max:180'],
			'description'      => ['nullable', 'string'],
			'quantity'         => ['bail', 'nullable', 'numeric', 'min:0.01'],
			'unit'             => ['nullable', 'string', 'max:50'],
			'start_time'       => ['nullable', 'date_format:H:i', 'required_with:end_time'],
			'end_time'         => ['nullable', 'date_format:H:i', 'required_with:start_time', 'different:start_time'],
			'duration_minutes' => ['nullable', 'integer', 'min:0'],
			'status'           => ['bail', 'required', 'string', Rule::in(EmployeeActivity::availableStatuses())],
			'verified_by'      => ['nullable', 'integer', 'exists:users,id', 'required_with:verified_at'],
			'verified_at'      => ['nullable', 'date', 'required_with:verified_by'],
		];
	}

	public function withValidator(Validator $validator): void
	{
		$validator->after(function (Validator $validator): void {
			$status = (string) ($this->input('status') ?? EmployeeActivity::STATUS_SUBMITTED);
			$verifiedBy = $this->input('verified_by');
			$verifiedAt = $this->input('verified_at');

			$requiresVerification = in_array(
				$status,
				[
					EmployeeActivity::STATUS_VERIFIED,
					EmployeeActivity::STATUS_REJECTED,
				],
				true
			);

			if ($requiresVerification) {
				if ($verifiedBy === null) {
					$validator->errors()->add(
						'verified_by',
						'User verifikator wajib diisi untuk status verified atau rejected.'
					);
				}

				if ($verifiedAt === null) {
					$validator->errors()->add(
						'verified_at',
						'Tanggal verifikasi wajib diisi untuk status verified atau rejected.'
					);
				}
			}

			if ($status === EmployeeActivity::STATUS_SUBMITTED) {
				if ($verifiedBy !== null || $verifiedAt !== null) {
					$validator->errors()->add(
						'status',
						'Status submitted tidak boleh memiliki data verifikasi.'
					);
				}
			}
		});
	}

	public function messages(): array
	{
		return [
			'employee_id.required'      => 'Pegawai wajib dipilih.',
			'employee_id.exists'        => 'Pegawai yang dipilih tidak ditemukan.',
			'service_order_id.exists'   => 'Service order yang dipilih tidak ditemukan.',
			'activity_date.required'    => 'Tanggal aktivitas wajib diisi.',
			'activity_date.date'        => 'Tanggal aktivitas tidak valid.',
			'activity_name.required'    => 'Nama aktivitas wajib diisi.',
			'activity_name.max'         => 'Nama aktivitas maksimal 180 karakter.',
			'quantity.numeric'          => 'Kuantitas harus berupa angka.',
			'quantity.min'              => 'Kuantitas minimal 0.01.',
			'unit.max'                  => 'Satuan maksimal 50 karakter.',
			'start_time.date_format'    => 'Format jam mulai harus HH:MM.',
			'start_time.required_with'  => 'Jam mulai wajib diisi jika jam selesai diisi.',
			'end_time.date_format'      => 'Format jam selesai harus HH:MM.',
			'end_time.required_with'    => 'Jam selesai wajib diisi jika jam mulai diisi.',
			'end_time.different'        => 'Jam selesai tidak boleh sama dengan jam mulai.',
			'duration_minutes.integer'  => 'Durasi menit harus berupa angka bulat.',
			'duration_minutes.min'      => 'Durasi menit minimal 0.',
			'status.required'           => 'Status aktivitas wajib dipilih.',
			'status.in'                 => 'Status aktivitas tidak valid.',
			'verified_by.exists'        => 'User verifikator tidak ditemukan.',
			'verified_by.required_with' => 'User verifikator wajib diisi bila tanggal verifikasi diisi.',
			'verified_at.date'          => 'Tanggal verifikasi tidak valid.',
			'verified_at.required_with' => 'Tanggal verifikasi wajib diisi bila verifikator diisi.',
		];
	}

	public function attributes(): array
	{
		return [
			'employee_id' => 'pegawai',
			'service_order_id' => 'service order',
			'activity_date' => 'tanggal aktivitas',
			'activity_name' => 'nama aktivitas',
			'description' => 'deskripsi',
			'quantity' => 'kuantitas',
			'unit' => 'satuan',
			'start_time' => 'jam mulai',
			'end_time' => 'jam selesai',
			'duration_minutes' => 'durasi menit',
			'status' => 'status',
			'verified_by' => 'verifikator',
			'verified_at' => 'tanggal verifikasi',
		];
	}

	private function normalizeNullableString(mixed $value): ?string
	{
		if ($value === null) {
			return null;
		}

		$normalized = trim((string) $value);

		return $normalized === '' ? null : $normalized;
	}

	private function normalizeNullableInteger(mixed $value): ?int
	{
		if ($value === null || $value === '') {
			return null;
		}

		return is_numeric($value) ? (int) $value : null;
	}

	private function normalizeNullableTime(mixed $value): ?string
	{
		$normalized = $this->normalizeNullableString($value);

		return $normalized === null ? null : substr($normalized, 0, 5);
	}
}

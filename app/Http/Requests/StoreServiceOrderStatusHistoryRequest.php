<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\ServiceOrder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreServiceOrderStatusHistoryRequest extends FormRequest
{
	public function authorize(): bool
	{
		return true;
	}

	protected function prepareForValidation(): void
	{
		$payload = [];

		if ($this->has('previous_status')) {
			$payload['previous_status'] = strtolower(trim((string) $this->input('previous_status')));
		}

		if ($this->has('new_status')) {
			$payload['new_status'] = strtolower(trim((string) $this->input('new_status')));
		}

		if ($this->has('notes')) {
			$notes = trim((string) $this->input('notes'));
			$payload['notes'] = $notes === '' ? null : $notes;
		}

		if ($payload !== []) {
			$this->merge($payload);
		}
	}

	public function rules(): array
	{
		$statuses = [
			ServiceOrder::ORDER_STATUS_DRAFT,
			ServiceOrder::ORDER_STATUS_PENDING,
			ServiceOrder::ORDER_STATUS_PROCESSING,
			ServiceOrder::ORDER_STATUS_COMPLETED,
			ServiceOrder::ORDER_STATUS_CANCELLED,
		];

		return [
			'service_order_id' => ['required', 'integer', 'exists:service_orders,id'],
			'previous_status' => ['nullable', 'string', Rule::in($statuses)],
			'new_status' => ['required', 'string', Rule::in($statuses)],
			'notes' => ['nullable', 'string', 'max:2000'],
			'changed_by' => ['nullable', 'integer', 'exists:users,id'],
			'changed_at' => ['nullable', 'date'],
		];
	}

	public function withValidator(Validator $validator): void
	{
		$validator->after(function (Validator $validator): void {
			$previousStatus = $this->input('previous_status');
			$newStatus = $this->input('new_status');

			if (is_string($previousStatus) && is_string($newStatus) && $previousStatus === $newStatus) {
				$validator->errors()->add('new_status', 'Status baru harus berbeda dari status sebelumnya.');
			}
		});
	}

	public function messages(): array
	{
		return [
			'service_order_id.required' => 'Service order wajib dipilih.',
			'service_order_id.exists' => 'Service order tidak ditemukan.',
			'new_status.required' => 'Status baru wajib diisi.',
			'new_status.in' => 'Status baru tidak valid.',
			'previous_status.in' => 'Status sebelumnya tidak valid.',
			'notes.max' => 'Catatan maksimal 2000 karakter.',
			'changed_by.exists' => 'User pengubah tidak ditemukan.',
			'changed_at.date' => 'Tanggal perubahan tidak valid.',
		];
	}
}

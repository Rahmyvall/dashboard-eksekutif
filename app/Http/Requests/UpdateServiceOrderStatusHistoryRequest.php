<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\ServiceOrder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UpdateServiceOrderStatusHistoryRequest extends FormRequest
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
			'previous_status' => ['sometimes', 'nullable', 'string', Rule::in($statuses)],
			'new_status' => ['sometimes', 'string', Rule::in($statuses)],
			'notes' => ['sometimes', 'nullable', 'string', 'max:2000'],
			'changed_by' => ['sometimes', 'nullable', 'integer', 'exists:users,id'],
			'changed_at' => ['sometimes', 'date'],
		];
	}

	public function withValidator(Validator $validator): void
	{
		$validator->after(function (Validator $validator): void {
			$history = $this->route('serviceOrderStatusHistory');

			$previousStatus = $this->has('previous_status')
				? $this->input('previous_status')
				: (is_object($history) ? $history->previous_status : null);

			$newStatus = $this->has('new_status')
				? $this->input('new_status')
				: (is_object($history) ? $history->new_status : null);

			if (is_string($previousStatus) && is_string($newStatus) && $previousStatus === $newStatus) {
				$validator->errors()->add('new_status', 'Status baru harus berbeda dari status sebelumnya.');
			}
		});
	}

	public function messages(): array
	{
		return [
			'new_status.in' => 'Status baru tidak valid.',
			'previous_status.in' => 'Status sebelumnya tidak valid.',
			'notes.max' => 'Catatan maksimal 2000 karakter.',
			'changed_by.exists' => 'User pengubah tidak ditemukan.',
			'changed_at.date' => 'Tanggal perubahan tidak valid.',
		];
	}
}

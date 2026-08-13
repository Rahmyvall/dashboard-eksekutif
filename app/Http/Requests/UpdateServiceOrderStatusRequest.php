<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\ServiceOrder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UpdateServiceOrderStatusRequest extends FormRequest
{
	/**
	 * @var array<string, array<int, string>>
	 */
	private const ALLOWED_TRANSITIONS = [
		ServiceOrder::ORDER_STATUS_DRAFT => [
			ServiceOrder::ORDER_STATUS_PENDING,
			ServiceOrder::ORDER_STATUS_CANCELLED,
		],
		ServiceOrder::ORDER_STATUS_PENDING => [
			ServiceOrder::ORDER_STATUS_DRAFT,
			ServiceOrder::ORDER_STATUS_PROCESSING,
			ServiceOrder::ORDER_STATUS_CANCELLED,
		],
		ServiceOrder::ORDER_STATUS_PROCESSING => [
			ServiceOrder::ORDER_STATUS_PENDING,
			ServiceOrder::ORDER_STATUS_COMPLETED,
			ServiceOrder::ORDER_STATUS_CANCELLED,
		],
		ServiceOrder::ORDER_STATUS_COMPLETED => [],
		ServiceOrder::ORDER_STATUS_CANCELLED => [],
	];

	public function authorize(): bool
	{
		return true;
	}

	protected function prepareForValidation(): void
	{
		$payload = [];

		if ($this->has('order_status')) {
			$payload['order_status'] = strtolower(trim((string) $this->input('order_status')));
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
		return [
			'order_status' => [
				'required',
				'string',
				Rule::in([
					ServiceOrder::ORDER_STATUS_DRAFT,
					ServiceOrder::ORDER_STATUS_PENDING,
					ServiceOrder::ORDER_STATUS_PROCESSING,
					ServiceOrder::ORDER_STATUS_COMPLETED,
					ServiceOrder::ORDER_STATUS_CANCELLED,
				]),
			],
			'notes' => ['nullable', 'string', 'max:2000'],
		];
	}

	public function withValidator(Validator $validator): void
	{
		$validator->after(function (Validator $validator): void {
			$serviceOrder = $this->resolveServiceOrder();
			if (! $serviceOrder instanceof ServiceOrder) {
				return;
			}

			$targetStatus = (string) $this->input('order_status');
			$currentStatus = (string) $serviceOrder->order_status;

			if ($targetStatus === $currentStatus) {
				$validator->errors()->add('order_status', 'Status pesanan tidak berubah dari status saat ini.');
				return;
			}

			$allowedTargets = self::ALLOWED_TRANSITIONS[$currentStatus] ?? [];

			if (! in_array($targetStatus, $allowedTargets, true)) {
				$validator->errors()->add(
					'order_status',
					sprintf(
						'Perubahan status dari "%s" ke "%s" tidak diizinkan.',
						$currentStatus,
						$targetStatus
					)
				);
			}
		});
	}

	public function messages(): array
	{
		return [
			'order_status.required' => 'Status pesanan wajib diisi.',
			'order_status.in' => 'Status pesanan tidak valid.',
			'notes.max' => 'Catatan maksimal 2000 karakter.',
		];
	}

	private function resolveServiceOrder(): ?ServiceOrder
	{
		$routeCandidates = [
			$this->route('serviceOrder'),
			$this->route('service_order'),
		];

		foreach ($routeCandidates as $candidate) {
			if ($candidate instanceof ServiceOrder) {
				return $candidate;
			}
		}

		return null;
	}
}

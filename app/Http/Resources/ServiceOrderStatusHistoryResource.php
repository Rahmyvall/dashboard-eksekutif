<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\ServiceOrderStatusHistory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin ServiceOrderStatusHistory */
class ServiceOrderStatusHistoryResource extends JsonResource
{
	public function toArray(Request $request): array
	{
		return [
			'id' => (int) $this->id,
			'service_order_id' => (int) $this->service_order_id,
			'previous_status' => $this->previous_status,
			'previous_status_label' => $this->statusLabel($this->previous_status),
			'new_status' => $this->new_status,
			'new_status_label' => $this->statusLabel($this->new_status),
			'notes' => $this->notes,
			'changed_by' => $this->changed_by,
			'changed_at' => $this->changed_at?->toISOString(),
			'is_initial_status' => $this->previous_status === null,
			'service_order' => $this->whenLoaded('serviceOrder', function (): ?array {
				if ($this->serviceOrder === null) {
					return null;
				}

				return [
					'id' => (int) $this->serviceOrder->id,
					'order_number' => $this->serviceOrder->order_number,
					'order_status' => $this->serviceOrder->order_status,
					'payment_status' => $this->serviceOrder->payment_status,
				];
			}),
			'changed_by_user' => $this->whenLoaded('changedBy', function (): ?array {
				if ($this->changedBy === null) {
					return null;
				}

				return [
					'id' => (int) $this->changedBy->id,
					'name' => $this->changedBy->name,
					'email' => $this->changedBy->email,
				];
			}),
			'created_at' => $this->created_at?->toISOString(),
			'updated_at' => $this->updated_at?->toISOString(),
		];
	}

	private function statusLabel(?string $status): ?string
	{
		if ($status === null) {
			return null;
		}

		return match ($status) {
			'draft' => 'Draft',
			'pending' => 'Menunggu Proses',
			'processing' => 'Diproses',
			'completed' => 'Selesai',
			'cancelled' => 'Dibatalkan',
			default => ucfirst(str_replace('_', ' ', $status)),
		};
	}
}
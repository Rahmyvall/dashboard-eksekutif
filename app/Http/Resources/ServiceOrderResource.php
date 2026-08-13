<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\ServiceOrder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin ServiceOrder */
class ServiceOrderResource extends JsonResource
{
	public function toArray(Request $request): array
	{
		return [
			'id' => (int) $this->id,
			'order_number' => $this->order_number,
			'customer_id' => (int) $this->customer_id,
			'order_date' => $this->order_date?->format('Y-m-d'),
			'scheduled_date' => $this->scheduled_date?->format('Y-m-d'),
			'completion_date' => $this->completion_date?->format('Y-m-d'),
			'order_status' => $this->order_status,
			'order_status_label' => $this->orderStatusLabel((string) $this->order_status),
			'payment_status' => $this->payment_status,
			'payment_status_label' => $this->paymentStatusLabel((string) $this->payment_status),
			'subtotal' => (float) $this->subtotal,
			'discount' => (float) $this->discount,
			'tax' => (float) $this->tax,
			'total_amount' => (float) $this->total_amount,
			'paid_amount' => (float) $this->paid_amount,
			'remaining_amount' => (float) $this->remaining_amount,
			'formatted_total_amount' => $this->formatted_total_amount,
			'formatted_remaining_amount' => $this->formatted_remaining_amount,
			'notes' => $this->notes,
			'created_by' => $this->created_by,
			'customer' => $this->whenLoaded('customer', function (): ?array {
				if ($this->customer === null) {
					return null;
				}

				return [
					'id' => (int) $this->customer->id,
					'customer_code' => $this->customer->customer_code,
					'name' => $this->customer->name,
					'company_name' => $this->customer->company_name,
					'display_name' => $this->customer->company_name ?: $this->customer->name,
				];
			}),
			'creator' => $this->whenLoaded('creator', function (): ?array {
				if ($this->creator === null) {
					return null;
				}

				return [
					'id' => (int) $this->creator->id,
					'name' => $this->creator->name,
					'email' => $this->creator->email,
				];
			}),
			'items' => $this->whenLoaded('items', function (): array {
				return $this->items->map(static function ($item): array {
					return [
						'id' => (int) $item->id,
						'service_order_id' => (int) $item->service_order_id,
						'service_id' => (int) $item->service_id,
						'employee_id' => $item->employee_id,
						'quantity' => (float) $item->quantity,
						'unit_price' => (float) $item->unit_price,
						'discount' => (float) $item->discount,
						'subtotal' => (float) $item->subtotal,
						'status' => $item->status,
						'start_date' => $item->start_date?->format('Y-m-d'),
						'completion_date' => $item->completion_date?->format('Y-m-d'),
						'notes' => $item->notes,
					];
				})->values()->all();
			}),
			'invoice' => $this->whenLoaded('invoice', function (): ?array {
				if ($this->invoice === null) {
					return null;
				}

				return [
					'id' => (int) $this->invoice->id,
					'invoice_number' => $this->invoice->invoice_number,
					'invoice_date' => $this->invoice->invoice_date?->format('Y-m-d'),
					'due_date' => $this->invoice->due_date?->format('Y-m-d'),
					'total_amount' => (float) $this->invoice->total_amount,
					'payment_status' => $this->invoice->payment_status,
				];
			}),
			'payments' => $this->whenLoaded('payments', function (): array {
				return $this->payments->map(static function ($payment): array {
					return [
						'id' => (int) $payment->id,
						'payment_number' => $payment->payment_number,
						'payment_date' => $payment->payment_date?->format('Y-m-d'),
						'payment_method' => $payment->payment_method,
						'amount' => (float) $payment->amount,
						'status' => $payment->status,
					];
				})->values()->all();
			}),
			'status_histories' => ServiceOrderStatusHistoryResource::collection(
				$this->whenLoaded('statusHistories')
			),
			'created_at' => $this->created_at?->toISOString(),
			'updated_at' => $this->updated_at?->toISOString(),
			'deleted_at' => $this->deleted_at?->toISOString(),
		];
	}

	private function orderStatusLabel(string $status): string
	{
		return match ($status) {
			ServiceOrder::ORDER_STATUS_DRAFT => 'Draft',
			ServiceOrder::ORDER_STATUS_PENDING => 'Menunggu Proses',
			ServiceOrder::ORDER_STATUS_PROCESSING => 'Diproses',
			ServiceOrder::ORDER_STATUS_COMPLETED => 'Selesai',
			ServiceOrder::ORDER_STATUS_CANCELLED => 'Dibatalkan',
			default => ucfirst(str_replace('_', ' ', $status)),
		};
	}

	private function paymentStatusLabel(string $status): string
	{
		return match ($status) {
			ServiceOrder::PAYMENT_STATUS_UNPAID => 'Belum Dibayar',
			ServiceOrder::PAYMENT_STATUS_PARTIAL => 'Dibayar Sebagian',
			ServiceOrder::PAYMENT_STATUS_PAID => 'Lunas',
			default => ucfirst(str_replace('_', ' ', $status)),
		};
	}
}

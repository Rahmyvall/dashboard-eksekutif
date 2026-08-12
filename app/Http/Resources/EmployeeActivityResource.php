<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\EmployeeActivity;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin EmployeeActivity */
class EmployeeActivityResource extends JsonResource
{
	public function toArray(Request $request): array
	{
		return [
			'id' => $this->id,
			'employee_id' => $this->employee_id,
			'service_order_id' => $this->service_order_id,
			'activity_date' => $this->activity_date?->format('Y-m-d'),
			'activity_name' => $this->activity_name,
			'description' => $this->description,
			'quantity' => (float) $this->quantity,
			'unit' => $this->unit,
			'start_time' => empty($this->start_time) ? null : substr((string) $this->start_time, 0, 5),
			'end_time' => empty($this->end_time) ? null : substr((string) $this->end_time, 0, 5),
			'time_range' => $this->getTimeRangeLabel(),
			'duration_minutes' => (int) $this->duration_minutes,
			'duration_hours' => $this->getDurationHours(),
			'status' => $this->status,
			'status_label' => $this->getStatusLabel(),
			'is_verified' => $this->isVerified(),
			'is_overnight' => $this->isOvernight(),
			'verified_by' => $this->verified_by,
			'verified_at' => $this->verified_at?->toISOString(),
			'employee' => $this->whenLoaded('employee', function (): ?array {
				if ($this->employee === null) {
					return null;
				}

				return [
					'id' => $this->employee->id,
					'employee_number' => $this->employee->employee_number,
					'full_name' => $this->employee->full_name,
					'status' => $this->employee->status,
					'department' => $this->employee->department?->name,
				];
			}),
			'service_order' => $this->whenLoaded('serviceOrder', function (): ?array {
				if ($this->serviceOrder === null) {
					return null;
				}

				return [
					'id' => $this->serviceOrder->id,
					'order_number' => $this->serviceOrder->order_number,
					'order_status' => $this->serviceOrder->order_status,
					'order_date' => $this->serviceOrder->order_date?->format('Y-m-d'),
					'customer' => $this->serviceOrder->customer?->company_name
						?: $this->serviceOrder->customer?->name,
				];
			}),
			'verifier' => $this->whenLoaded('verifier', function (): ?array {
				if ($this->verifier === null) {
					return null;
				}

				return [
					'id' => $this->verifier->id,
					'name' => $this->verifier->name,
					'email' => $this->verifier->email,
				];
			}),
			'created_at' => $this->created_at?->toISOString(),
			'updated_at' => $this->updated_at?->toISOString(),
		];
	}
}

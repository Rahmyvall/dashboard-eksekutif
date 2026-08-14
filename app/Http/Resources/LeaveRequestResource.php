<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @mixin LeaveRequest
 */
final class LeaveRequestResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getKey(),
            'employee_id' => (int) $this->employee_id,
            'leave_type' => (string) $this->leave_type,
            'start_date' => $this->start_date?->toDateString(),
            'end_date' => $this->end_date?->toDateString(),
            'total_days' => (int) $this->total_days,
            'reason' => (string) $this->reason,
            'status' => (string) $this->status,
            'attachment_path' => $this->attachment_path,
            'attachment_url' => filled($this->attachment_path)
                ? Storage::disk('public')->url((string) $this->attachment_path)
                : null,
            'approved_by' => $this->approved_by,
            'approved_at' => $this->approved_at?->toISOString(),

            'employee' => $this->whenLoaded('employee', fn () => [
                'id' => $this->employee?->getKey(),
                'full_name' => $this->employee?->full_name,
                'employee_number' => $this->employee?->employee_number,
                'department' => $this->employee?->relationLoaded('department') ? [
                    'id' => $this->employee?->department?->getKey(),
                    'name' => $this->employee?->department?->name,
                ] : null,
                'position' => $this->employee?->relationLoaded('position') ? [
                    'id' => $this->employee?->position?->getKey(),
                    'name' => $this->employee?->position?->name,
                ] : null,
            ]),

            'approver' => $this->whenLoaded('approver', fn () => [
                'id' => $this->approver?->getKey(),
                'name' => $this->approver?->name,
                'email' => $this->approver?->email,
            ]),

            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}

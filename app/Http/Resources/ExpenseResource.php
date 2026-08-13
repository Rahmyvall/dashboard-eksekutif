<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Expense
 */
final class ExpenseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->getKey(),
            'expense_date'     => $this->expense_date?->toDateString(),
            'category'         => $this->category,
            'description'      => $this->description,
            'amount'           => (float) $this->amount,
            'formatted_amount' => $this->formatted_amount,
            'attachment_url'   => $this->attachment_url,
            'has_attachment'   => filled($this->attachment_path),

            'service_order' => $this->whenLoaded('serviceOrder', fn () => [
                'id'           => $this->serviceOrder->getKey(),
                'order_number' => $this->serviceOrder->order_number,
                'customer'     => $this->serviceOrder->relationLoaded('customer') ? [
                    'id'   => $this->serviceOrder->customer?->getKey(),
                    'name' => $this->serviceOrder->customer?->name,
                ] : null,
            ]),

            'creator' => $this->whenLoaded('creator', fn () => [
                'id'   => $this->creator?->getKey(),
                'name' => $this->creator?->name,
            ]),

            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}

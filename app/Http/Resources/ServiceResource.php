<?php

declare (strict_types = 1);

namespace App\Http\Resources;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Service */
class ServiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                         => $this->id,
            'service_category_id'        => $this->service_category_id,
            'service_code'               => $this->service_code,
            'name'                       => $this->name,
            'description'                => $this->description,
            'base_price'                 => $this->base_price,
            'formatted_price'            => $this->formatted_price,
            'estimated_duration_minutes' => $this->estimated_duration_minutes,
            'unit'                       => $this->unit,
            'status'                     => $this->status,
            'status_label'               => $this->status_label,
            'is_active'                  => $this->status === Service::STATUS_ACTIVE,
            'category'                   => $this->whenLoaded('category', function (): array {
                return [
                    'id'     => $this->category->id,
                    'code'   => $this->category->code,
                    'name'   => $this->category->name,
                    'status' => $this->category->status,
                ];
            }),
            'created_at'                 => $this->created_at?->toISOString(),
            'updated_at'                 => $this->updated_at?->toISOString(),
            'deleted_at'                 => $this->deleted_at?->toISOString(),
        ];
    }
}

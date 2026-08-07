<?php

declare (strict_types = 1);

namespace App\Http\Resources;

use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin ServiceCategory
 */
class ServiceCategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,

            'code'         => $this->code,

            'name'         => $this->name,

            'description'  => $this->description,

            'status'       => $this->status,

            'status_label' => match ($this->status) {
                ServiceCategory::STATUS_ACTIVE   => 'Aktif',
                ServiceCategory::STATUS_INACTIVE => 'Tidak Aktif',
                default                          => ucfirst((string) $this->status),
            },

            'is_active'    =>
            $this->status === ServiceCategory::STATUS_ACTIVE,

            'created_at'   =>
            $this->created_at?->toISOString(),

            'updated_at'   =>
            $this->updated_at?->toISOString(),

            'deleted_at'   =>
            $this->deleted_at?->toISOString(),
        ];
    }
}

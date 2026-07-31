<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PositionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'department_id' => $this->department_id,
            'code'          => $this->code,
            'name'          => $this->name,
            'level'         => $this->level,
            'description'   => $this->description,
            'status'        => $this->status,

            'department'    => $this->whenLoaded('department', function (): ?array {
                if (! $this->department) {
                    return null;
                }

                return [
                    'id'     => $this->department->id,
                    'code'   => $this->department->code,
                    'name'   => $this->department->name,
                    'status' => $this->department->status,
                ];
            }),

            'created_at'    => $this->created_at?->toISOString(),
            'updated_at'    => $this->updated_at?->toISOString(),
            'deleted_at'    => $this->deleted_at?->toISOString(),
        ];
    }
}

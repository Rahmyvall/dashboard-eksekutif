<?php

declare (strict_types = 1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Department
 */
class DepartmentResource extends JsonResource
{
    /**
     * Nama pembungkus utama respons JSON.
     */
    public static $wrap = 'data';

    /**
     * Ubah model Department menjadi struktur JSON API.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => (int) $this->id,
            'code'         => (string) $this->code,
            'name'         => (string) $this->name,
            'description'  => $this->description,

            'status'       => (string) $this->status,
            'status_label' => match ($this->status) {
                'active'   => 'Aktif',
                'inactive' => 'Tidak Aktif',
                default    => ucfirst((string) $this->status),
            },

            'is_active'    => $this->status === 'active',
            'is_deleted'   => $this->deleted_at !== null,

            'created_at'   => $this->created_at?->toISOString(),
            'updated_at'   => $this->updated_at?->toISOString(),
            'deleted_at'   => $this->deleted_at?->toISOString(),
        ];
    }
}

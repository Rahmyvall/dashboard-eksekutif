<?php

declare (strict_types = 1);

namespace App\Http\Resources;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Customer
 */
final class CustomerResource extends JsonResource
{
    /**
     * Mengubah model pelanggan menjadi respons JSON API.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $isCompany = $this->customer_type
        === Customer::TYPE_COMPANY;

        $isActive = $this->status
        === Customer::STATUS_ACTIVE;

        return [
            'id'                  => $this->getKey(),
            'customer_code'       => $this->customer_code,
            'customer_type'       => $this->customer_type,
            'customer_type_label' => $isCompany
                ? 'Perusahaan'
                : 'Perorangan',
            'name'                => $this->name,
            'display_name'        => $isCompany && filled($this->company_name)
                ? $this->company_name
                : $this->name,
            'company_name'        => $this->company_name,
            'phone'               => $this->phone,
            'email'               => $this->email,
            'address'             => $this->address,
            'tax_number'          => $this->tax_number,
            'status'              => $this->status,
            'status_label'        => $isActive
                ? 'Aktif'
                : 'Tidak Aktif',
            'is_active'           => $isActive,
            'is_deleted'          => $this->trashed(),
            'created_at'          => $this->created_at?->toISOString(),
            'updated_at'          => $this->updated_at?->toISOString(),
            'deleted_at'          => $this->deleted_at?->toISOString(),
        ];
    }
}

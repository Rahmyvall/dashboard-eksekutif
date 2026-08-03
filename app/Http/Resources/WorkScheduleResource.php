<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkScheduleResource extends JsonResource
{
    /**
     * Ubah resource menjadi array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                     => $this->id,
            'name'                   => $this->name,

            // Mengubah 08:00:00 menjadi 08:00.
            'start_time'             => substr((string) $this->start_time, 0, 5),
            'end_time'               => substr((string) $this->end_time, 0, 5),

            'late_tolerance_minutes' => (int) $this->late_tolerance_minutes,
            'working_hours'          => (float) $this->working_hours,
            'status'                 => $this->status,

            'created_at'             => $this->created_at?->toISOString(),
            'updated_at'             => $this->updated_at?->toISOString(),
        ];
    }
}

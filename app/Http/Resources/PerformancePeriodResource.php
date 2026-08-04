<?php

declare (strict_types = 1);

namespace App\Http\Resources;

use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource JSON untuk tabel performance_periods.
 *
 * @mixin \App\Models\PerformancePeriod
 */
class PerformancePeriodResource extends JsonResource
{
    /**
     * Mengubah model PerformancePeriod menjadi struktur JSON.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $startDate = $this->start_date
            ? CarbonImmutable::parse($this->start_date)
            : null;

        $endDate = $this->end_date
            ? CarbonImmutable::parse($this->end_date)
            : null;

        $today = CarbonImmutable::today();

        $periodType = strtolower(
            trim((string) $this->period_type)
        );

        $status = strtolower(
            trim((string) $this->status)
        );

        $periodTypeLabel = match ($periodType) {
            'monthly'   => 'Bulanan',
            'quarterly' => 'Kuartalan',
            'semester'  => 'Semester',
            'annual'    => 'Tahunan',
            default     => ucfirst($periodType),
        };

        $statusLabel = match ($status) {
            'draft'     => 'Draft',
            'active'    => 'Aktif',
            'completed' => 'Selesai',
            'inactive'  => 'Tidak Aktif',
            default     => ucfirst($status),
        };

        $periodState = match (true) {
            $startDate === null || $endDate === null => 'unknown',

            $today->lt(
                $startDate->startOfDay()
            )                                        => 'upcoming',

            $today->gt(
                $endDate->endOfDay()
            )                                        => 'expired',

            default                                  => 'current',
        };

        $durationDays = $startDate && $endDate
            ? (int) $startDate->diffInDays($endDate) + 1
            : null;

        $isCurrent = $periodState === 'current'
            && $status === 'active';

        return [
            'id'                => (int) $this->id,

            'name'              => (string) $this->name,

            'start_date'        => $startDate?->format('Y-m-d'),

            'end_date'          => $endDate?->format('Y-m-d'),

            'period_type'       => $periodType,

            'period_type_label' => $periodTypeLabel,

            'status'            => $status,

            'status_label'      => $statusLabel,

            'duration_days'     => $durationDays,

            /*
             * upcoming = belum dimulai
             * current  = sedang berlangsung
             * expired  = sudah berakhir
             * unknown  = tanggal tidak lengkap
             */
            'period_state'      => $periodState,

            /*
             * Bernilai true jika status active dan tanggal hari ini
             * berada di antara start_date dan end_date.
             */
            'is_current'        => $isCurrent,

            'created_at'        => $this->created_at
                ? CarbonImmutable::parse(
                $this->created_at
            )->toISOString()
                : null,

            'updated_at'        => $this->updated_at
                ? CarbonImmutable::parse(
                $this->updated_at
            )->toISOString()
                : null,
        ];
    }
}

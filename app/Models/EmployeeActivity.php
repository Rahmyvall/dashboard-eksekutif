<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeActivity extends Model
{
    use HasFactory;

    public const STATUS_SUBMITTED = 'submitted';
    public const STATUS_VERIFIED = 'verified';
    public const STATUS_REJECTED = 'rejected';

    public const DEFAULT_QUANTITY = 1;

    /**
     * Nama tabel.
     */
    protected $table = 'employee_activities';

    /**
     * Primary key.
     */
    protected $primaryKey = 'id';

    /**
     * Primary key menggunakan auto increment.
     */
    public $incrementing = true;

    /**
     * Tipe primary key.
     */
    protected $keyType = 'int';

    /**
     * Laravel akan menggunakan created_at dan updated_at.
     */
    public $timestamps = true;

    /**
     * Kolom yang boleh diisi melalui mass assignment.
     */
    protected $fillable = [
        'employee_id',
        'service_order_id',
        'activity_date',
        'activity_name',
        'description',
        'quantity',
        'unit',
        'start_time',
        'end_time',
        'duration_minutes',
        'status',
        'verified_by',
        'verified_at',
    ];

    /**
     * Default attribute values.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'quantity' => self::DEFAULT_QUANTITY,
        'duration_minutes' => 0,
        'status' => self::STATUS_SUBMITTED,
    ];

    /**
     * Casting tipe data.
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'employee_id' => 'integer',
            'service_order_id' => 'integer',

            'activity_date' => 'date',

            'quantity' => 'decimal:2',

            'duration_minutes' => 'integer',

            'verified_by' => 'integer',
            'verified_at' => 'datetime',

            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    /**
     * Pegawai yang melakukan aktivitas.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(
            Employee::class,
            'employee_id',
            'id'
        );
    }

    /**
     * Service order dari aktivitas.
     */
    public function serviceOrder(): BelongsTo
    {
        return $this->belongsTo(
            ServiceOrder::class,
            'service_order_id',
            'id'
        );
    }

    /**
     * User yang melakukan verifikasi.
     */
    public function verifier(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'verified_by',
            'id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | QUERY SCOPES
    |--------------------------------------------------------------------------
    */

    /**
     * Filter berdasarkan pegawai.
     */
    public function scopeEmployee(
        Builder $query,
        int $employeeId
    ): Builder {
        return $query->where('employee_id', $employeeId);
    }

    /**
     * Filter berdasarkan service order.
     */
    public function scopeServiceOrder(
        Builder $query,
        int $serviceOrderId
    ): Builder {
        return $query->where(
            'service_order_id',
            $serviceOrderId
        );
    }

    /**
     * Filter berdasarkan status.
     */
    public function scopeStatus(
        Builder $query,
        string $status
    ): Builder {
        return $query->where('status', $status);
    }

    /**
     * Filter berdasarkan tanggal aktivitas.
     */
    public function scopeActivityDate(
        Builder $query,
        string $date
    ): Builder {
        return $query->whereDate(
            'activity_date',
            $date
        );
    }

    /**
     * Filter range tanggal.
     */
    public function scopeDateRange(
        Builder $query,
        string $startDate,
        string $endDate
    ): Builder {
        return $query->whereBetween(
            'activity_date',
            [
                $startDate,
                $endDate,
            ]
        );
    }

    /**
     * Aktivitas yang sudah diverifikasi.
     */
    public function scopeVerified(
        Builder $query
    ): Builder {
        return $query
            ->whereNotNull('verified_by')
            ->whereNotNull('verified_at');
    }

    /**
     * Aktivitas yang belum diverifikasi.
     */
    public function scopeUnverified(
        Builder $query
    ): Builder {
        return $query->where(function ($q) {
            $q->whereNull('verified_by')
              ->orWhereNull('verified_at');
        });
    }

    /**
     * Alias untuk aktivitas yang menunggu verifikasi.
     */
    public function scopePendingVerification(Builder $query): Builder
    {
        return $this->scopeUnverified($query);
    }

    /**
     * Urutkan berdasarkan aktivitas terbaru.
     */
    public function scopeLatestActivity(Builder $query): Builder
    {
        return $query
            ->orderByDesc('activity_date')
            ->orderByDesc('start_time')
            ->orderByDesc('id');
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER METHODS
    |--------------------------------------------------------------------------
    */

    /**
     * Mengecek apakah aktivitas sudah diverifikasi.
     */
    public function isVerified(): bool
    {
        return !is_null($this->verified_by)
            && !is_null($this->verified_at);
    }

    /**
     * Mengecek apakah aktivitas lintas hari.
     */
    public function isOvernight(): bool
    {
        if (empty($this->start_time) || empty($this->end_time)) {
            return false;
        }

        return substr((string) $this->end_time, 0, 5)
            <= substr((string) $this->start_time, 0, 5);
    }

    /**
     * Mendapatkan label status yang lebih ramah dibaca.
     */
    public function getStatusLabel(): string
    {
        return match ((string) $this->status) {
            self::STATUS_SUBMITTED => 'Submitted',
            self::STATUS_VERIFIED => 'Verified',
            self::STATUS_REJECTED => 'Rejected',
            default => ucfirst(str_replace('_', ' ', (string) $this->status)),
        };
    }

    /**
     * Mendapatkan durasi dalam satuan jam.
     */
    public function getDurationHours(): float
    {
        return round(((int) $this->duration_minutes) / 60, 2);
    }

    /**
     * Mendapatkan rentang waktu singkat untuk ditampilkan.
     */
    public function getTimeRangeLabel(): string
    {
        $startTime = empty($this->start_time)
            ? '—'
            : substr((string) $this->start_time, 0, 5);

        $endTime = empty($this->end_time)
            ? '—'
            : substr((string) $this->end_time, 0, 5);

        return $startTime . ' - ' . $endTime;
    }

    /**
     * Daftar status yang didukung model.
     *
     * @return list<string>
     */
    public static function availableStatuses(): array
    {
        return [
            self::STATUS_SUBMITTED,
            self::STATUS_VERIFIED,
            self::STATUS_REJECTED,
        ];
    }

    /**
     * Melakukan verifikasi aktivitas.
     */
    public function verify(
        int $verifiedBy,
        ?string $status = null
    ): bool {
        $this->verified_by = $verifiedBy;
        $this->verified_at = now();

        if ($status !== null) {
            $this->status = $status;
        }

        return $this->save();
    }

    /**
     * Membatalkan verifikasi.
     */
    public function cancelVerification(): bool
    {
        $this->verified_by = null;
        $this->verified_at = null;

        return $this->save();
    }

    /**
     * Menghitung durasi aktivitas berdasarkan start_time dan end_time.
     */
    public function calculateDuration(): ?int
    {
        if (
            empty($this->start_time) ||
            empty($this->end_time)
        ) {
            return null;
        }

        $start = Carbon::parse($this->start_time);
        $end = Carbon::parse($this->end_time);

        /**
         * Jika aktivitas melewati tengah malam.
         * Contoh:
         * 23:00 - 01:00
         */
        if ($end->lessThan($start)) {
            $end->addDay();
        }

        return (int) $start->diffInMinutes($end);
    }

    /**
     * Update duration_minutes secara otomatis.
     */
    public function updateDuration(): bool
    {
        $this->duration_minutes = $this->calculateDuration();

        return $this->save();
    }

    /*
    |--------------------------------------------------------------------------
    | MODEL EVENTS
    |--------------------------------------------------------------------------
    */

    protected static function booted(): void
    {
        /**
         * Hitung duration_minutes otomatis sebelum data disimpan.
         */
        static::saving(function (EmployeeActivity $activity) {

            if (
                !empty($activity->start_time) &&
                !empty($activity->end_time)
            ) {
                $activity->duration_minutes =
                    $activity->calculateDuration();
            }
        });
    }
}
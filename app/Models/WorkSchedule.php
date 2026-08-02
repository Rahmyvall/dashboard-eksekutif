<?php
namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model WorkSchedule
 *
 * @property int $id
 * @property string $name
 * @property string $start_time
 * @property string $end_time
 * @property int $late_tolerance_minutes
 * @property string $working_hours
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class WorkSchedule extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | Status
    |--------------------------------------------------------------------------
    */

    public const STATUS_ACTIVE = 'active';

    public const STATUS_INACTIVE = 'inactive';

    /*
    |--------------------------------------------------------------------------
    | Konfigurasi Tabel
    |--------------------------------------------------------------------------
    */

    /**
     * Nama tabel database.
     */
    protected $table = 'work_schedules';

    /**
     * Primary key tabel.
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
     * Mengaktifkan created_at dan updated_at.
     */
    public $timestamps = true;

    /*
    |--------------------------------------------------------------------------
    | Mass Assignment
    |--------------------------------------------------------------------------
    */

    /**
     * Kolom yang diperbolehkan untuk create dan update.
     */
    protected $fillable = [
        'name',
        'start_time',
        'end_time',
        'late_tolerance_minutes',
        'working_hours',
        'status',
    ];

    /*
    |--------------------------------------------------------------------------
    | Attribute Casting
    |--------------------------------------------------------------------------
    */

    /**
     * Konversi tipe data atribut.
     */
    protected function casts(): array
    {
        return [
            'id'                     => 'integer',
            'late_tolerance_minutes' => 'integer',
            'working_hours'          => 'decimal:2',
            'created_at'             => 'datetime',
            'updated_at'             => 'datetime',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Default Attributes
    |--------------------------------------------------------------------------
    */

    /**
     * Nilai default ketika membuat jadwal kerja.
     */
    protected $attributes = [
        'late_tolerance_minutes' => 0,
        'working_hours'          => 0,
        'status'                 => self::STATUS_ACTIVE,
    ];

    /*
    |--------------------------------------------------------------------------
    | Query Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * Mengambil jadwal berstatus aktif.
     *
     * Contoh:
     * WorkSchedule::active()->get();
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Mengambil jadwal berstatus tidak aktif.
     */
    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_INACTIVE);
    }

    /**
     * Melakukan pencarian berdasarkan nama jadwal.
     *
     * Contoh:
     * WorkSchedule::search('Pagi')->get();
     */
    public function scopeSearch(
        Builder $query,
        ?string $keyword
    ): Builder {
        return $query->when(
            filled($keyword),
            function (Builder $query) use ($keyword): void {
                $query->where('name', 'like', '%' . trim($keyword) . '%');
            }
        );
    }

    /**
     * Filter jadwal berdasarkan status.
     */
    public function scopeStatus(
        Builder $query,
        ?string $status
    ): Builder {
        return $query->when(
            filled($status),
            fn(Builder $query): Builder => $query->where('status', $status)
        );
    }

    /**
     * Mengurutkan berdasarkan jam masuk.
     */
    public function scopeOrderByStartTime(
        Builder $query,
        string $direction = 'asc'
    ): Builder {
        $direction = strtolower($direction) === 'desc' ? 'desc' : 'asc';

        return $query->orderBy('start_time', $direction);
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Memeriksa apakah jadwal aktif.
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Memeriksa apakah jadwal tidak aktif.
     */
    public function isInactive(): bool
    {
        return $this->status === self::STATUS_INACTIVE;
    }

    /**
     * Memeriksa apakah jadwal melewati tengah malam.
     *
     * Contoh:
     * 20:00:00 sampai 04:00:00.
     */
    public function crossesMidnight(): bool
    {
        $startTime = $this->parseTime($this->start_time);
        $endTime   = $this->parseTime($this->end_time);

        if (! $startTime || ! $endTime) {
            return false;
        }

        return $endTime->lessThanOrEqualTo($startTime);
    }

    /**
     * Menghitung total durasi kerja dalam menit.
     */
    public function getWorkingDurationInMinutes(): int
    {
        $startTime = $this->parseTime($this->start_time);
        $endTime   = $this->parseTime($this->end_time);

        if (! $startTime || ! $endTime) {
            return 0;
        }

        if ($endTime->lessThanOrEqualTo($startTime)) {
            $endTime->addDay();
        }

        return (int) $startTime->diffInMinutes($endTime);
    }

    /**
     * Menghitung total durasi kerja dalam jam.
     */
    public function getCalculatedWorkingHours(): float
    {
        return round(
            $this->getWorkingDurationInMinutes() / 60,
            2
        );
    }

    /**
     * Mendapatkan batas akhir keterlambatan.
     *
     * Contoh:
     * Jam masuk 08:00 dan toleransi 15 menit,
     * batas keterlambatan adalah 08:15.
     */
    public function getLateLimitTime(): ?string
    {
        $startTime = $this->parseTime($this->start_time);

        if (! $startTime) {
            return null;
        }

        return $startTime
            ->addMinutes($this->late_tolerance_minutes)
            ->format('H:i:s');
    }

    /**
     * Memeriksa apakah pegawai terlambat.
     *
     * Parameter dapat berupa:
     * - 08:10:00
     * - objek Carbon
     */
    public function isLate(string | Carbon $checkInTime): bool
    {
        $startTime = $this->parseTime($this->start_time);

        if (! $startTime) {
            return false;
        }

        $lateLimit = $startTime
            ->copy()
            ->addMinutes($this->late_tolerance_minutes);

        if ($checkInTime instanceof Carbon) {
            $employeeCheckIn = $checkInTime->copy();
        } else {
            $employeeCheckIn = $this->parseTime($checkInTime);
        }

        if (! $employeeCheckIn) {
            return false;
        }

        return $employeeCheckIn->greaterThan($lateLimit);
    }

    /**
     * Format jam masuk menjadi HH:mm.
     */
    public function getFormattedStartTime(): string
    {
        return $this->formatTime($this->start_time);
    }

    /**
     * Format jam pulang menjadi HH:mm.
     */
    public function getFormattedEndTime(): string
    {
        return $this->formatTime($this->end_time);
    }

    /**
     * Mendapatkan label status.
     */
    public function getStatusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_ACTIVE   => 'Aktif',
            self::STATUS_INACTIVE => 'Tidak Aktif',
            default               => ucfirst($this->status),
        };
    }

    /**
     * Mendapatkan seluruh pilihan status.
     */
    public static function statusOptions(): array
    {
        return [
            self::STATUS_ACTIVE   => 'Aktif',
            self::STATUS_INACTIVE => 'Tidak Aktif',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Private Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Mengubah string waktu menjadi objek Carbon.
     */
    private function parseTime(?string $time): ?Carbon
    {
        if (blank($time)) {
            return null;
        }

        foreach (['H:i:s', 'H:i'] as $format) {
            try {
                return Carbon::createFromFormat($format, $time);
            } catch (\Throwable) {
                // Coba format selanjutnya.
            }
        }

        return null;
    }

    /**
     * Mengubah format waktu menjadi HH:mm.
     */
    private function formatTime(?string $time): string
    {
        $parsedTime = $this->parseTime($time);

        return $parsedTime
            ? $parsedTime->format('H:i')
            : '-';
    }
}

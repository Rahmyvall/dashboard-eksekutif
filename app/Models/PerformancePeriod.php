<?php
namespace App\Models;

use Carbon\CarbonImmutable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model PerformancePeriod
 *
 * @property int $id
 * @property string $name
 * @property \Carbon\CarbonImmutable $start_date
 * @property \Carbon\CarbonImmutable $end_date
 * @property string $period_type
 * @property string $status
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 *
 * @method static Builder|PerformancePeriod byStatus(string $status)
 * @method static Builder|PerformancePeriod byType(string $periodType)
 * @method static Builder|PerformancePeriod containingDate(DateTimeInterface|string $date)
 * @method static Builder|PerformancePeriod current()
 * @method static Builder|PerformancePeriod upcoming()
 * @method static Builder|PerformancePeriod expired()
 */
class PerformancePeriod extends Model
{
    use HasFactory;

    /**
     * Nama tabel database.
     */
    protected $table = 'performance_periods';

    /**
     * Primary key tabel.
     */
    protected $primaryKey = 'id';

    /**
     * Menandakan primary key menggunakan auto increment.
     */
    public $incrementing = true;

    /**
     * Tipe data primary key.
     */
    protected $keyType = 'int';

    /**
     * Mengaktifkan created_at dan updated_at.
     */
    public $timestamps = true;

    /**
     * Atribut yang boleh diisi melalui mass assignment.
     */
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'period_type',
        'status',
    ];

    /**
     * Konversi tipe data atribut.
     */
    protected $casts = [
        'id'         => 'integer',
        'start_date' => 'immutable_date:Y-m-d',
        'end_date'   => 'immutable_date:Y-m-d',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Query Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * Filter berdasarkan status.
     *
     * Contoh:
     * PerformancePeriod::byStatus('active')->get();
     */
    public function scopeByStatus(
        Builder $query,
        string $status
    ): Builder {
        return $query->where('status', $status);
    }

    /**
     * Filter berdasarkan tipe periode.
     *
     * Contoh:
     * PerformancePeriod::byType('annual')->get();
     */
    public function scopeByType(
        Builder $query,
        string $periodType
    ): Builder {
        return $query->where('period_type', $periodType);
    }

    /**
     * Mengambil periode yang mencakup tanggal tertentu.
     *
     * Contoh:
     * PerformancePeriod::containingDate('2026-08-03')->get();
     */
    public function scopeContainingDate(
        Builder $query,
        DateTimeInterface | string $date
    ): Builder {
        $date = CarbonImmutable::parse($date)->toDateString();

        return $query
            ->whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date);
    }

    /**
     * Mengambil periode yang sedang berlangsung berdasarkan tanggal hari ini.
     */
    public function scopeCurrent(Builder $query): Builder
    {
        $today = CarbonImmutable::now()->toDateString();

        return $query
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today);
    }

    /**
     * Mengambil periode yang belum dimulai.
     */
    public function scopeUpcoming(Builder $query): Builder
    {
        return $query
            ->whereDate(
                'start_date',
                '>',
                CarbonImmutable::now()->toDateString()
            )
            ->orderBy('start_date');
    }

    /**
     * Mengambil periode yang sudah berakhir.
     */
    public function scopeExpired(Builder $query): Builder
    {
        return $query
            ->whereDate(
                'end_date',
                '<',
                CarbonImmutable::now()->toDateString()
            )
            ->orderByDesc('end_date');
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Memeriksa apakah periode memiliki status tertentu.
     */
    public function hasStatus(string $status): bool
    {
        return $this->status === $status;
    }

    /**
     * Memeriksa apakah periode memiliki tipe tertentu.
     */
    public function hasType(string $periodType): bool
    {
        return $this->period_type === $periodType;
    }

    /**
     * Memeriksa apakah suatu tanggal berada dalam rentang periode.
     */
    public function containsDate(
        DateTimeInterface | string $date
    ): bool {
        $date = CarbonImmutable::parse($date)->startOfDay();

        return $date->greaterThanOrEqualTo(
            $this->start_date->startOfDay()
        ) && $date->lessThanOrEqualTo(
            $this->end_date->endOfDay()
        );
    }

    /**
     * Memeriksa apakah periode sudah dimulai.
     */
    public function hasStarted(): bool
    {
        return $this->start_date
            ->startOfDay()
            ->lessThanOrEqualTo(CarbonImmutable::now());
    }

    /**
     * Memeriksa apakah periode sudah berakhir.
     */
    public function hasEnded(): bool
    {
        return $this->end_date
            ->endOfDay()
            ->lessThan(CarbonImmutable::now());
    }

    /**
     * Memeriksa apakah periode sedang berlangsung.
     *
     * Pemeriksaan hanya berdasarkan rentang tanggal,
     * tidak berdasarkan isi kolom status.
     */
    public function isCurrent(): bool
    {
        return $this->containsDate(CarbonImmutable::now());
    }

    /**
     * Menghitung jumlah hari periode secara inklusif.
     *
     * Contoh:
     * 1 Agustus sampai 3 Agustus menghasilkan 3 hari.
     */
    public function durationInDays(): int
    {
        return (int) $this->start_date
            ->diffInDays($this->end_date) + 1;
    }
}

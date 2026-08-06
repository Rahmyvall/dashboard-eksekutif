<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model PerformanceIndicator
 *
 * Merepresentasikan data indikator kinerja pada tabel
 * performance_indicators.
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string|null $description
 * @property string $unit
 * @property string $weight
 * @property string $target_direction
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class PerformanceIndicator extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan model.
     */
    protected $table = 'performance_indicators';

    /**
     * Primary key tabel.
     */
    protected $primaryKey = 'id';

    /**
     * Tipe primary key.
     */
    protected $keyType = 'int';

    /**
     * Menandakan primary key menggunakan auto increment.
     */
    public $incrementing = true;

    /**
     * Menandakan tabel memiliki created_at dan updated_at.
     */
    public $timestamps = true;

    /**
     * Kolom yang dapat diisi melalui mass assignment.
     */
    protected $fillable = [
        'code',
        'name',
        'description',
        'unit',
        'weight',
        'target_direction',
        'status',
    ];

    /**
     * Konversi tipe data secara otomatis.
     *
     * Decimal digunakan agar nilai bobot tetap presisi.
     */
    protected $casts = [
        'id'         => 'integer',
        'weight'     => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Target Direction Constants
    |--------------------------------------------------------------------------
    */

    /**
     * Semakin besar nilai aktual, semakin baik.
     */
    public const DIRECTION_INCREASE = 'increase';

    /**
     * Semakin kecil nilai aktual, semakin baik.
     */
    public const DIRECTION_DECREASE = 'decrease';

    /**
     * Nilai aktual harus sama atau mendekati target.
     */
    public const DIRECTION_EXACT = 'exact';

    /*
    |--------------------------------------------------------------------------
    | Status Constants
    |--------------------------------------------------------------------------
    */

    /**
     * Indikator aktif dan dapat digunakan.
     */
    public const STATUS_ACTIVE = 'active';

    /**
     * Indikator tidak aktif.
     */
    public const STATUS_INACTIVE = 'inactive';

    /*
    |--------------------------------------------------------------------------
    | Boot Method
    |--------------------------------------------------------------------------
    */

    /**
     * Menjalankan proses otomatis ketika model dibuat atau diperbarui.
     */
    protected static function booted(): void
    {
        static::creating(function (PerformanceIndicator $indicator): void {
            $indicator->normalizeAttributes();
        });

        static::updating(function (PerformanceIndicator $indicator): void {
            $indicator->normalizeAttributes();
        });
    }

    /**
     * Normalisasi data sebelum disimpan.
     */
    private function normalizeAttributes(): void
    {
        if ($this->code !== null) {
            $this->code = strtoupper(trim($this->code));
        }

        if ($this->name !== null) {
            $this->name = trim($this->name);
        }

        if ($this->unit !== null) {
            $this->unit = trim($this->unit);
        }

        if ($this->description !== null) {
            $description = trim($this->description);

            $this->description = $description !== ''
                ? $description
                : null;
        }

        if ($this->target_direction !== null) {
            $this->target_direction = strtolower(
                trim($this->target_direction)
            );
        }

        if ($this->status !== null) {
            $this->status = strtolower(trim($this->status));
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Query Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * Mengambil indikator yang berstatus aktif.
     *
     * Contoh:
     * PerformanceIndicator::active()->get();
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where(
            'status',
            self::STATUS_ACTIVE
        );
    }

    /**
     * Mengambil indikator yang tidak aktif.
     *
     * Contoh:
     * PerformanceIndicator::inactive()->get();
     */
    public function scopeInactive(Builder $query): Builder
    {
        return $query->where(
            'status',
            self::STATUS_INACTIVE
        );
    }

    /**
     * Filter berdasarkan arah target.
     *
     * Contoh:
     * PerformanceIndicator::direction('increase')->get();
     */
    public function scopeDirection(
        Builder $query,
        string $direction
    ): Builder {
        return $query->where(
            'target_direction',
            strtolower(trim($direction))
        );
    }

    /**
     * Filter indikator yang arah targetnya increase.
     */
    public function scopeIncrease(Builder $query): Builder
    {
        return $query->where(
            'target_direction',
            self::DIRECTION_INCREASE
        );
    }

    /**
     * Filter indikator yang arah targetnya decrease.
     */
    public function scopeDecrease(Builder $query): Builder
    {
        return $query->where(
            'target_direction',
            self::DIRECTION_DECREASE
        );
    }

    /**
     * Filter indikator yang arah targetnya exact.
     */
    public function scopeExact(Builder $query): Builder
    {
        return $query->where(
            'target_direction',
            self::DIRECTION_EXACT
        );
    }

    /**
     * Pencarian berdasarkan kode, nama, deskripsi, atau satuan.
     *
     * Contoh:
     * PerformanceIndicator::search('kehadiran')->get();
     */
    public function scopeSearch(
        Builder $query,
        ?string $keyword
    ): Builder {
        $keyword = trim((string) $keyword);

        if ($keyword === '') {
            return $query;
        }

        return $query->where(function (Builder $subQuery) use ($keyword): void {
            $subQuery
                ->where('code', 'ILIKE', "%{$keyword}%")
                ->orWhere('name', 'ILIKE', "%{$keyword}%")
                ->orWhere('description', 'ILIKE', "%{$keyword}%")
                ->orWhere('unit', 'ILIKE', "%{$keyword}%");
        });
    }

    /**
     * Filter berdasarkan status.
     */
    public function scopeStatus(
        Builder $query,
        ?string $status
    ): Builder {
        if ($status === null || trim($status) === '') {
            return $query;
        }

        return $query->where(
            'status',
            strtolower(trim($status))
        );
    }

    /**
     * Mengurutkan indikator berdasarkan kode.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('code');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    /**
     * Mendapatkan label arah target.
     *
     * Pemanggilan:
     * $indicator->target_direction_label
     */
    public function getTargetDirectionLabelAttribute(): string
    {
        return match ($this->target_direction) {
            self::DIRECTION_INCREASE => 'Semakin Besar Semakin Baik',
            self::DIRECTION_DECREASE => 'Semakin Kecil Semakin Baik',
            self::DIRECTION_EXACT    => 'Harus Sesuai Target',
            default                  => 'Tidak Diketahui',
        };
    }

    /**
     * Mendapatkan label status.
     *
     * Pemanggilan:
     * $indicator->status_label
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_ACTIVE   => 'Aktif',
            self::STATUS_INACTIVE => 'Tidak Aktif',
            default               => 'Tidak Diketahui',
        };
    }

    /**
     * Mendapatkan bobot dalam format dua angka desimal.
     *
     * Pemanggilan:
     * $indicator->formatted_weight
     */
    public function getFormattedWeightAttribute(): string
    {
        return number_format(
            (float) $this->weight,
            2,
            ',',
            '.'
        );
    }

    /**
     * Mendapatkan bobot dengan simbol persen.
     *
     * Pemanggilan:
     * $indicator->weight_percentage
     */
    public function getWeightPercentageAttribute(): string
    {
        return number_format(
            (float) $this->weight,
            2,
            ',',
            '.'
        ) . '%';
    }

    /*
    |--------------------------------------------------------------------------
    | Status Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Mengecek apakah indikator aktif.
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Mengecek apakah indikator tidak aktif.
     */
    public function isInactive(): bool
    {
        return $this->status === self::STATUS_INACTIVE;
    }

    /**
     * Mengaktifkan indikator.
     */
    public function activate(): bool
    {
        return $this->update([
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Menonaktifkan indikator.
     */
    public function deactivate(): bool
    {
        return $this->update([
            'status' => self::STATUS_INACTIVE,
        ]);
    }

    /**
     * Mengubah status indikator.
     */
    public function toggleStatus(): bool
    {
        return $this->update([
            'status' => $this->isActive()
                ? self::STATUS_INACTIVE
                : self::STATUS_ACTIVE,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Target Direction Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Mengecek apakah arah target increase.
     */
    public function isIncreaseDirection(): bool
    {
        return $this->target_direction === self::DIRECTION_INCREASE;
    }

    /**
     * Mengecek apakah arah target decrease.
     */
    public function isDecreaseDirection(): bool
    {
        return $this->target_direction === self::DIRECTION_DECREASE;
    }

    /**
     * Mengecek apakah arah target exact.
     */
    public function isExactDirection(): bool
    {
        return $this->target_direction === self::DIRECTION_EXACT;
    }

    /*
    |--------------------------------------------------------------------------
    | Static Option Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Daftar pilihan arah target untuk dropdown.
     */
    public static function targetDirectionOptions(): array
    {
        return [
            self::DIRECTION_INCREASE => 'Semakin Besar Semakin Baik',
            self::DIRECTION_DECREASE => 'Semakin Kecil Semakin Baik',
            self::DIRECTION_EXACT    => 'Harus Sesuai Target',
        ];
    }

    /**
     * Daftar pilihan status untuk dropdown.
     */
    public static function statusOptions(): array
    {
        return [
            self::STATUS_ACTIVE   => 'Aktif',
            self::STATUS_INACTIVE => 'Tidak Aktif',
        ];
    }

    /**
     * Daftar nilai arah target yang valid.
     */
    public static function validTargetDirections(): array
    {
        return [
            self::DIRECTION_INCREASE,
            self::DIRECTION_DECREASE,
            self::DIRECTION_EXACT,
        ];
    }

    /**
     * Daftar nilai status yang valid.
     */
    public static function validStatuses(): array
    {
        return [
            self::STATUS_ACTIVE,
            self::STATUS_INACTIVE,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Business Logic Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Menghitung nilai capaian berdasarkan arah target.
     *
     * Rumus:
     * increase = aktual / target × 100
     * decrease = target / aktual × 100
     * exact    = 100 - persentase selisih aktual terhadap target
     */
    public function calculateAchievement(
        float $actualValue,
        float $targetValue
    ): float {
        if ($targetValue < 0 || $actualValue < 0) {
            return 0;
        }

        $achievement = match ($this->target_direction) {
            self::DIRECTION_INCREASE => $this->calculateIncreaseAchievement(
                $actualValue,
                $targetValue
            ),

            self::DIRECTION_DECREASE => $this->calculateDecreaseAchievement(
                $actualValue,
                $targetValue
            ),

            self::DIRECTION_EXACT    => $this->calculateExactAchievement(
                $actualValue,
                $targetValue
            ),

            default                  => 0,
        };

        return round(max(0, $achievement), 2);
    }

    /**
     * Menghitung capaian untuk target increase.
     */
    private function calculateIncreaseAchievement(
        float $actualValue,
        float $targetValue
    ): float {
        if ($targetValue == 0.0) {
            return $actualValue == 0.0 ? 100 : 0;
        }

        return ($actualValue / $targetValue) * 100;
    }

    /**
     * Menghitung capaian untuk target decrease.
     */
    private function calculateDecreaseAchievement(
        float $actualValue,
        float $targetValue
    ): float {
        if ($actualValue == 0.0) {
            return $targetValue >= 0 ? 100 : 0;
        }

        if ($targetValue == 0.0) {
            return $actualValue == 0.0 ? 100 : 0;
        }

        return ($targetValue / $actualValue) * 100;
    }

    /**
     * Menghitung capaian untuk target exact.
     */
    private function calculateExactAchievement(
        float $actualValue,
        float $targetValue
    ): float {
        if ($targetValue == 0.0) {
            return $actualValue == 0.0 ? 100 : 0;
        }

        $differencePercentage = (
            abs($actualValue - $targetValue) / abs($targetValue)
        ) * 100;

        return 100 - $differencePercentage;
    }

    /**
     * Menghitung nilai akhir berdasarkan capaian dan bobot.
     */
    public function calculateWeightedScore(
        float $actualValue,
        float $targetValue
    ): float {
        $achievement = $this->calculateAchievement(
            $actualValue,
            $targetValue
        );

        return round(
            ($achievement * (float) $this->weight) / 100,
            2
        );
    }
}

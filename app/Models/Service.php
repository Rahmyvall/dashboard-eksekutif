<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUS_ACTIVE = 'active';

    public const STATUS_INACTIVE = 'inactive';

    /**
     * Nama tabel
     */
    protected $table = 'services';

    /**
     * Field yang boleh diisi
     */
    protected $fillable = [
        'service_category_id',
        'service_code',
        'name',
        'description',
        'base_price',
        'estimated_duration_minutes',
        'unit',
        'status',
    ];

    protected $attributes = [
        'base_price' => 0,
        'unit'       => 'service',
        'status'     => self::STATUS_ACTIVE,
    ];

    protected static function booted(): void
    {
        static::creating(function (self $service): void {
            if (blank($service->service_code)) {
                $service->service_code = self::nextServiceCode();
            }
        });
    }

    /**
     * Casting tipe data
     */
    protected function casts(): array
    {
        return [
            'id'                         => 'integer',
            'service_category_id'        => 'integer',
            'base_price'                 => 'decimal:2',
            'estimated_duration_minutes' => 'integer',
            'created_at'                 => 'datetime',
            'updated_at'                 => 'datetime',
            'deleted_at'                 => 'datetime',
        ];
    }

    public function setServiceCodeAttribute(string $value): void
    {
        $this->attributes['service_code'] = strtoupper(trim($value));
    }

    /**
     * Menghasilkan kode service berikutnya secara otomatis.
     *
     * Data soft delete tetap diperiksa agar kode tidak pernah terulang.
     */
    public static function nextServiceCode(): string
    {
        $lastNumber = self::withTrashed()
            ->pluck('service_code')
            ->map(function ($code): int {
                preg_match('/(\d+)$/', (string) $code, $matches);

                return isset($matches[1]) ? (int) $matches[1] : 0;
            })
            ->max();

        return 'SVC-' . str_pad((string) ($lastNumber + 1), 3, '0', STR_PAD_LEFT);
    }

    public function setNameAttribute(string $value): void
    {
        $this->attributes['name'] = trim($value);
    }

    public function setUnitAttribute(string $value): void
    {
        $this->attributes['unit'] = trim($value);
    }

    public function setStatusAttribute(string $value): void
    {
        $this->attributes['status'] = strtolower(trim($value));
    }

    /**
     * Relasi ke service_categories
     *
     * services
     *      belongsTo
     * service_categories
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(
            ServiceCategory::class,
            'service_category_id'
        );
    }

    /**
     * Relasi ke service_order_items
     *
     * services
     *      hasMany
     * service_order_items
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(
            ServiceOrderItem::class,
            'service_id'
        );
    }

    /**
     * Scope service aktif
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_INACTIVE);
    }

    public function scopeStatus(Builder $query, ?string $status): Builder
    {
        return $query->when(
            filled($status),
            fn(Builder $query): Builder => $query->where('status', strtolower($status))
        );
    }

    public function scopeSearch(Builder $query, ?string $keyword): Builder
    {
        return $query->when(filled($keyword), function (Builder $query) use ($keyword): void {
            $query->where(function (Builder $query) use ($keyword): void {
                $query->where('service_code', 'like', "%{$keyword}%")
                    ->orWhere('name', 'like', "%{$keyword}%")
                    ->orWhere('unit', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%");
            });
        });
    }

    /**
     * Format harga
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format(
            (float) $this->base_price,
            0,
            ',',
            '.'
        );
    }

    /**
     * Status service
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_ACTIVE   => 'Aktif',
            self::STATUS_INACTIVE => 'Tidak Aktif',
            default               => ucfirst((string) $this->status),
        };
    }

    public static function statuses(): array
    {
        return [
            self::STATUS_ACTIVE   => 'Aktif',
            self::STATUS_INACTIVE => 'Tidak Aktif',
        ];
    }

    public function activate(): bool
    {
        return $this->update(['status' => self::STATUS_ACTIVE]);
    }

    public function deactivate(): bool
    {
        return $this->update(['status' => self::STATUS_INACTIVE]);
    }
}

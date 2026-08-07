<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceCategory extends Model
{
    use HasFactory, SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | Nama tabel
    |--------------------------------------------------------------------------
    */

    protected $table = 'service_categories';

    /*
    |--------------------------------------------------------------------------
    | Primary key
    |--------------------------------------------------------------------------
    */

    protected $primaryKey = 'id';

    /**
     * Menentukan bahwa primary key menggunakan auto increment.
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

    /*
    |--------------------------------------------------------------------------
    | Status
    |--------------------------------------------------------------------------
    */

    public const STATUS_ACTIVE = 'active';

    public const STATUS_INACTIVE = 'inactive';

    /*
    |--------------------------------------------------------------------------
    | Mass assignment
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'code',
        'name',
        'description',
        'status',
    ];

    /*
    |--------------------------------------------------------------------------
    | Attribute casting
    |--------------------------------------------------------------------------
    */

    protected $casts = [
        'id'         => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Mutators
    |--------------------------------------------------------------------------
    */

    /**
     * Menyimpan code dalam format huruf kapital.
     *
     * Contoh:
     * svc-001 menjadi SVC-001
     */
    public function setCodeAttribute(string $value): void
    {
        $this->attributes['code'] = strtoupper(trim($value));
    }

    /**
     * Menghapus spasi berlebih pada nama kategori.
     */
    public function setNameAttribute(string $value): void
    {
        $this->attributes['name'] = trim($value);
    }

    /**
     * Menyimpan status dalam huruf kecil.
     *
     * Contoh:
     * ACTIVE menjadi active
     */
    public function setStatusAttribute(string $value): void
    {
        $this->attributes['status'] = strtolower(trim($value));
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    /**
     * Mendapatkan label status.
     *
     * Pemakaian:
     * $category->status_label
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_ACTIVE   => 'Aktif',
            self::STATUS_INACTIVE => 'Tidak Aktif',
            default               => ucfirst($this->status),
        };
    }

    /**
     * Memeriksa apakah kategori berstatus aktif.
     *
     * Pemakaian:
     * $category->is_active
     */
    public function getIsActiveAttribute(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /*
    |--------------------------------------------------------------------------
    | Query scopes
    |--------------------------------------------------------------------------
    */

    /**
     * Mengambil kategori yang aktif.
     *
     * Pemakaian:
     * ServiceCategory::active()->get();
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Mengambil kategori yang tidak aktif.
     *
     * Pemakaian:
     * ServiceCategory::inactive()->get();
     */
    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_INACTIVE);
    }

    /**
     * Filter berdasarkan status tertentu.
     *
     * Pemakaian:
     * ServiceCategory::status('active')->get();
     */
    public function scopeStatus(
        Builder $query,
        ?string $status
    ): Builder {
        return $query->when(
            filled($status),
            fn(Builder $query) => $query->where('status', $status)
        );
    }

    /**
     * Pencarian berdasarkan code, name, atau description.
     *
     * Pemakaian:
     * ServiceCategory::search('konsultasi')->get();
     */
    public function scopeSearch(
        Builder $query,
        ?string $keyword
    ): Builder {
        return $query->when(
            filled($keyword),
            function (Builder $query) use ($keyword): void {
                $query->where(function (Builder $query) use ($keyword): void {
                    $query
                        ->where('code', 'like', "%{$keyword}%")
                        ->orWhere('name', 'like', "%{$keyword}%")
                        ->orWhere('description', 'like', "%{$keyword}%");
                });
            }
        );
    }

    /**
     * Mengurutkan data terbaru.
     *
     * Pemakaian:
     * ServiceCategory::latestData()->get();
     */
    public function scopeLatestData(Builder $query): Builder
    {
        return $query->orderByDesc('created_at');
    }

    /*
    |--------------------------------------------------------------------------
    | Helper methods
    |--------------------------------------------------------------------------
    */

    /**
     * Daftar status yang diperbolehkan.
     */
    public static function statuses(): array
    {
        return [
            self::STATUS_ACTIVE   => 'Aktif',
            self::STATUS_INACTIVE => 'Tidak Aktif',
        ];
    }

    /**
     * Mengubah status menjadi aktif.
     */
    public function activate(): bool
    {
        return $this->update([
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Mengubah status menjadi tidak aktif.
     */
    public function deactivate(): bool
    {
        return $this->update([
            'status' => self::STATUS_INACTIVE,
        ]);
    }
}

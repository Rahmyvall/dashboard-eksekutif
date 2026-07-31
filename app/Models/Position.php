<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Position extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Nama tabel.
     */
    protected $table = 'positions';

    /**
     * Primary key.
     */
    protected $primaryKey = 'id';

    /**
     * Primary key bertipe integer dan auto increment.
     */
    public $incrementing = true;

    protected $keyType = 'int';

    /**
     * Kolom yang boleh diisi melalui mass assignment.
     */
    protected $fillable = [
        'department_id',
        'code',
        'name',
        'level',
        'description',
        'status',
    ];

    /**
     * Konversi tipe data.
     */
    protected $casts = [
        'id'            => 'integer',
        'department_id' => 'integer',
        'level'         => 'integer',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
        'deleted_at'    => 'datetime',
    ];

    /**
     * Konstanta status jabatan.
     */
    public const STATUS_ACTIVE = 'active';

    public const STATUS_INACTIVE = 'inactive';

    /**
     * Daftar status yang diperbolehkan.
     */
    public const STATUSES = [
        self::STATUS_ACTIVE,
        self::STATUS_INACTIVE,
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Relasi posisi/jabatan ke departemen.
     *
     * Satu posisi berada dalam satu departemen.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(
            Department::class,
            'department_id',
            'id'
        );
    }

    /**
     * Relasi posisi dengan pengguna.
     *
     * Aktifkan relasi ini jika tabel users memiliki kolom position_id.
     */
    public function users(): HasMany
    {
        return $this->hasMany(
            User::class,
            'position_id',
            'id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Query Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * Mengambil posisi dengan status aktif.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where(
            $this->qualifyColumn('status'),
            self::STATUS_ACTIVE
        );
    }

    /**
     * Mengambil posisi dengan status tidak aktif.
     */
    public function scopeInactive(Builder $query): Builder
    {
        return $query->where(
            $this->qualifyColumn('status'),
            self::STATUS_INACTIVE
        );
    }

    /**
     * Filter berdasarkan departemen.
     */
    public function scopeByDepartment(
        Builder $query,
        int | string | null $departmentId
    ): Builder {
        return $query->when(
            $departmentId,
            fn(Builder $query) => $query->where(
                $this->qualifyColumn('department_id'),
                $departmentId
            )
        );
    }

    /**
     * Filter berdasarkan level jabatan.
     */
    public function scopeByLevel(
        Builder $query,
        int | string | null $level
    ): Builder {
        return $query->when(
            $level !== null && $level !== '',
            fn(Builder $query) => $query->where(
                $this->qualifyColumn('level'),
                $level
            )
        );
    }

    /**
     * Pencarian berdasarkan kode, nama, atau deskripsi.
     *
     * Menggunakan ILIKE agar cocok untuk PostgreSQL.
     */
    public function scopeSearch(
        Builder $query,
        ?string $keyword
    ): Builder {
        $keyword = trim((string) $keyword);

        return $query->when(
            $keyword !== '',
            function (Builder $query) use ($keyword) {
                $query->where(function (Builder $query) use ($keyword) {
                    $query
                        ->where('code', 'ILIKE', "%{$keyword}%")
                        ->orWhere('name', 'ILIKE', "%{$keyword}%")
                        ->orWhere('description', 'ILIKE', "%{$keyword}%");
                });
            }
        );
    }

    /**
     * Mengurutkan posisi dari level tertinggi.
     */
    public function scopeHighestLevelFirst(Builder $query): Builder
    {
        return $query
            ->orderByDesc($this->qualifyColumn('level'))
            ->orderBy($this->qualifyColumn('name'));
    }

    /**
     * Mengurutkan berdasarkan nama.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query
            ->orderBy($this->qualifyColumn('name'));
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors dan Helper
    |--------------------------------------------------------------------------
    */

    /**
     * Memeriksa apakah posisi aktif.
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Memeriksa apakah posisi tidak aktif.
     */
    public function isInactive(): bool
    {
        return $this->status === self::STATUS_INACTIVE;
    }

    /**
     * Label status untuk ditampilkan di halaman.
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
     * Nama posisi beserta kode.
     *
     * Contoh:
     * MGR-CS - Manager Customer Service
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->code} - {$this->name}";
    }

    /**
     * Nama posisi beserta departemen.
     */
    public function getNameWithDepartmentAttribute(): string
    {
        $departmentName = $this->department?->name;

        if (! $departmentName) {
            return $this->name;
        }

        return "{$this->name} - {$departmentName}";
    }

    /**
     * Label level jabatan.
     */
    public function getLevelLabelAttribute(): string
    {
        return match ($this->level) {
            1       => 'Level 1 - Staff',
            2       => 'Level 2 - Senior Staff',
            3       => 'Level 3 - Supervisor',
            4       => 'Level 4 - Manager',
            5       => 'Level 5 - Direktur',
            default => "Level {$this->level}",
        };
    }
}

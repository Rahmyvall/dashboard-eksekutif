<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory, SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | KONFIGURASI MODEL
    |--------------------------------------------------------------------------
    */

    /**
     * Nama tabel yang digunakan oleh model.
     */
    protected $table = 'departments';

    /**
     * Primary key tabel.
     */
    protected $primaryKey = 'id';

    /**
     * Primary key menggunakan auto increment.
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
    | MASS ASSIGNMENT
    |--------------------------------------------------------------------------
    */

    /**
     * Kolom yang boleh diisi menggunakan create() atau update().
     */
    protected $fillable = [
        'code',
        'name',
        'description',
        'status',
    ];

    /**
     * Kolom yang tidak ditampilkan ketika model diubah menjadi array atau JSON.
     */
    protected $hidden = [
        'deleted_at',
    ];

    /*
    |--------------------------------------------------------------------------
    | CASTING
    |--------------------------------------------------------------------------
    */

    /**
     * Casting tipe data atribut.
     */
    protected function casts(): array
    {
        return [
            'id'         => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | DEFAULT ATTRIBUTE
    |--------------------------------------------------------------------------
    */

    /**
     * Nilai default atribut model.
     */
    protected $attributes = [
        'status' => 'active',
    ];

    /*
    |--------------------------------------------------------------------------
    | QUERY SCOPES
    |--------------------------------------------------------------------------
    */

    /**
     * Mengambil department yang aktif.
     *
     * Contoh:
     * Department::active()->get();
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    /**
     * Mengambil department yang tidak aktif.
     *
     * Contoh:
     * Department::inactive()->get();
     */
    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Filter berdasarkan status.
     *
     * Contoh:
     * Department::status('active')->get();
     */
    public function scopeStatus(
        Builder $query,
        string $status
    ): Builder {
        return $query->where('status', $status);
    }

    /**
     * Filter berdasarkan kode department.
     *
     * Contoh:
     * Department::code('IT')->first();
     */
    public function scopeCode(
        Builder $query,
        string $code
    ): Builder {
        return $query->where('code', $code);
    }

    /**
     * Pencarian berdasarkan kode, nama, atau deskripsi.
     *
     * Contoh:
     * Department::search('teknologi')->get();
     */
    public function scopeSearch(
        Builder $query,
        ?string $keyword
    ): Builder {
        $keyword = trim((string) $keyword);

        if ($keyword === '') {
            return $query;
        }

        return $query->where(function (Builder $subQuery) use ($keyword) {
            $subQuery
                ->where('code', 'like', "%{$keyword}%")
                ->orWhere('name', 'like', "%{$keyword}%")
                ->orWhere('description', 'like', "%{$keyword}%");
        });
    }

    /**
     * Mengurutkan department berdasarkan nama.
     *
     * Contoh:
     * Department::ordered()->get();
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('name');
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /**
     * Label status untuk ditampilkan pada halaman.
     *
     * Contoh:
     * {{ $department->status_label }}
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'active'   => 'Aktif',
            'inactive' => 'Tidak Aktif',
            default    => ucfirst((string) $this->status),
        };
    }

    /**
     * Class badge Bootstrap berdasarkan status.
     *
     * Contoh:
     * <span class="badge {{ $department->status_badge }}">
     */
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'active'   => 'bg-success',
            'inactive' => 'bg-secondary',
            default    => 'bg-warning text-dark',
        };
    }

    /**
     * Menampilkan kode dan nama department.
     *
     * Contoh hasil:
     * IT - Information Technology
     */
    public function getCodeNameAttribute(): string
    {
        return "{$this->code} - {$this->name}";
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

    /**
     * Menyimpan kode department dalam format huruf kapital.
     */
    public function setCodeAttribute(?string $value): void
    {
        $this->attributes['code'] = strtoupper(
            trim((string) $value)
        );
    }

    /**
     * Membersihkan spasi pada nama department.
     */
    public function setNameAttribute(?string $value): void
    {
        $this->attributes['name'] = trim(
            (string) $value
        );
    }

    /**
     * Menormalkan nilai status.
     */
    public function setStatusAttribute(?string $value): void
    {
        $status = strtolower(trim((string) $value));

        $this->attributes['status'] = in_array(
            $status,
            ['active', 'inactive'],
            true
        ) ? $status : 'active';
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER METHODS
    |--------------------------------------------------------------------------
    */

    /**
     * Memeriksa apakah department aktif.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Memeriksa apakah department tidak aktif.
     */
    public function isInactive(): bool
    {
        return $this->status === 'inactive';
    }

    /**
     * Mengaktifkan department.
     */
    public function activate(): bool
    {
        return $this->update([
            'status' => 'active',
        ]);
    }

    /**
     * Menonaktifkan department.
     */
    public function deactivate(): bool
    {
        return $this->update([
            'status' => 'inactive',
        ]);
    }

    /**
     * Relasi posisi pada departemen.
     */
    public function positions(): HasMany
    {
        return $this->hasMany(
            Position::class,
            'department_id',
            'id'
        );
    }

    /**
     * Posisi aktif pada departemen.
     */
    public function activePositions(): HasMany
    {
        return $this->hasMany(
            Position::class,
            'department_id',
            'id'
        )->where('status', Position::STATUS_ACTIVE);
    }
}

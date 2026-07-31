<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory;
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | Customer Type Constants
    |--------------------------------------------------------------------------
    */

    public const TYPE_INDIVIDUAL = 'individual';

    public const TYPE_COMPANY = 'company';

    /*
    |--------------------------------------------------------------------------
    | Customer Status Constants
    |--------------------------------------------------------------------------
    */

    public const STATUS_ACTIVE = 'active';

    public const STATUS_INACTIVE = 'inactive';

    /*
    |--------------------------------------------------------------------------
    | Mass Assignment
    |--------------------------------------------------------------------------
    */

    /**
     * Atribut yang diperbolehkan untuk mass assignment.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'customer_code',
        'customer_type',
        'name',
        'company_name',
        'phone',
        'email',
        'address',
        'tax_number',
        'status',
    ];

    /*
    |--------------------------------------------------------------------------
    | Default Attributes
    |--------------------------------------------------------------------------
    */

    /**
     * Nilai default atribut customer.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'customer_type' => self::TYPE_INDIVIDUAL,
        'status'        => self::STATUS_ACTIVE,
    ];

    /*
    |--------------------------------------------------------------------------
    | Attribute Casting
    |--------------------------------------------------------------------------
    */

    /**
     * Konversi tipe data atribut model.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    /**
     * Nama yang ditampilkan pada halaman aplikasi.
     *
     * Untuk customer company, company_name akan diprioritaskan.
     * Jika company_name kosong, sistem menggunakan nama customer.
     */
    protected function displayName(): Attribute
    {
        return Attribute::make(
            get: fn(): string => $this->customer_type === self::TYPE_COMPANY
            && filled($this->company_name)
                ? $this->company_name
                : $this->name
        );
    }

    /**
     * Label jenis customer dalam Bahasa Indonesia.
     */
    protected function customerTypeLabel(): Attribute
    {
        return Attribute::make(
            get: fn(): string => match ($this->customer_type) {
                self::TYPE_COMPANY    => 'Perusahaan',
                self::TYPE_INDIVIDUAL => 'Perorangan',
                default               => ucfirst((string) $this->customer_type),
            }
        );
    }

    /**
     * Label status customer dalam Bahasa Indonesia.
     */
    protected function statusLabel(): Attribute
    {
        return Attribute::make(
            get: fn(): string => match ($this->status) {
                self::STATUS_ACTIVE   => 'Aktif',
                self::STATUS_INACTIVE => 'Tidak Aktif',
                default               => ucfirst((string) $this->status),
            }
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Query Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * Mengambil customer dengan status aktif.
     */
    #[Scope]
    protected function active(Builder $query): void
    {
        $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Mengambil customer dengan status tidak aktif.
     */
    #[Scope]
    protected function inactive(Builder $query): void
    {
        $query->where('status', self::STATUS_INACTIVE);
    }

    /**
     * Mengambil customer perorangan.
     */
    #[Scope]
    protected function individual(Builder $query): void
    {
        $query->where('customer_type', self::TYPE_INDIVIDUAL);
    }

    /**
     * Mengambil customer perusahaan.
     */
    #[Scope]
    protected function company(Builder $query): void
    {
        $query->where('customer_type', self::TYPE_COMPANY);
    }

    /**
     * Mencari customer berdasarkan beberapa kolom.
     */
    #[Scope]
    protected function search(Builder $query, ?string $keyword): void
    {
        $keyword = trim((string) $keyword);

        if ($keyword === '') {
            return;
        }

        $query->where(function (Builder $subQuery) use ($keyword): void {
            $subQuery
                ->where('customer_code', 'ilike', "%{$keyword}%")
                ->orWhere('name', 'ilike', "%{$keyword}%")
                ->orWhere('company_name', 'ilike', "%{$keyword}%")
                ->orWhere('phone', 'ilike', "%{$keyword}%")
                ->orWhere('email', 'ilike', "%{$keyword}%")
                ->orWhere('tax_number', 'ilike', "%{$keyword}%");
        });
    }

    /**
     * Filter berdasarkan jenis customer.
     */
    #[Scope]
    protected function ofType(Builder $query, ?string $type): void
    {
        if (in_array($type, self::customerTypes(), true)) {
            $query->where('customer_type', $type);
        }
    }

    /**
     * Filter berdasarkan status customer.
     */
    #[Scope]
    protected function ofStatus(Builder $query, ?string $status): void
    {
        if (in_array($status, self::statuses(), true)) {
            $query->where('status', $status);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Memeriksa apakah customer aktif.
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Memeriksa apakah customer tidak aktif.
     */
    public function isInactive(): bool
    {
        return $this->status === self::STATUS_INACTIVE;
    }

    /**
     * Memeriksa apakah customer merupakan perorangan.
     */
    public function isIndividual(): bool
    {
        return $this->customer_type === self::TYPE_INDIVIDUAL;
    }

    /**
     * Memeriksa apakah customer merupakan perusahaan.
     */
    public function isCompany(): bool
    {
        return $this->customer_type === self::TYPE_COMPANY;
    }

    /**
     * Daftar jenis customer yang valid.
     *
     * @return array<int, string>
     */
    public static function customerTypes(): array
    {
        return [
            self::TYPE_INDIVIDUAL,
            self::TYPE_COMPANY,
        ];
    }

    /**
     * Daftar status customer yang valid.
     *
     * @return array<int, string>
     */
    public static function statuses(): array
    {
        return [
            self::STATUS_ACTIVE,
            self::STATUS_INACTIVE,
        ];
    }

    /**
     * Daftar pilihan jenis customer untuk form.
     *
     * @return array<string, string>
     */
    public static function customerTypeOptions(): array
    {
        return [
            self::TYPE_INDIVIDUAL => 'Perorangan',
            self::TYPE_COMPANY    => 'Perusahaan',
        ];
    }

    /**
     * Daftar pilihan status untuk form.
     *
     * @return array<string, string>
     */
    public static function statusOptions(): array
    {
        return [
            self::STATUS_ACTIVE   => 'Aktif',
            self::STATUS_INACTIVE => 'Tidak Aktif',
        ];
    }
}

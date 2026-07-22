<?php

declare (strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    /*
    |--------------------------------------------------------------------------
    | Status akun
    |--------------------------------------------------------------------------
    */

    public const STATUS_ACTIVE    = 'active';
    public const STATUS_INACTIVE  = 'inactive';
    public const STATUS_SUSPENDED = 'suspended';

    /*
    |--------------------------------------------------------------------------
    | Konfigurasi model
    |--------------------------------------------------------------------------
    */

    protected $table = 'users';

    protected $primaryKey = 'id';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = true;

    /*
    |--------------------------------------------------------------------------
    | Mass assignment
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'role_id',
        'name',
        'email',
        'email_verified_at',
        'password',
        'status',
        'last_login_at',
    ];

    /*
    |--------------------------------------------------------------------------
    | Hidden attributes
    |--------------------------------------------------------------------------
    */

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /*
    |--------------------------------------------------------------------------
    | Nilai default
    |--------------------------------------------------------------------------
    */

    protected $attributes = [
        'status' => self::STATUS_ACTIVE,
    ];

    /*
    |--------------------------------------------------------------------------
    | Casting
    |--------------------------------------------------------------------------
    */

    protected function casts(): array
    {
        return [
            'id'                => 'integer',
            'role_id'           => 'integer',
            'email_verified_at' => 'datetime',
            'last_login_at'     => 'datetime',
            'password'          => 'hashed',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Mutator
    |--------------------------------------------------------------------------
    */

    /**
     * Menyimpan email dengan format huruf kecil dan tanpa spasi.
     *
     * Hal ini membantu mencegah kegagalan login pada PostgreSQL
     * akibat perbedaan huruf besar dan kecil.
     */
    protected function email(): Attribute
    {
        return Attribute::make(
            set: static fn(mixed $value): string => strtolower(
                trim((string) $value)
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Relasi
    |--------------------------------------------------------------------------
    */

    /**
     * Role yang dimiliki user.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(
            Role::class,
            'role_id',
            'id'
        );
    }

    /**
     * Data karyawan yang terhubung dengan akun user.
     *
     * Kolom employees.user_id sebaiknya bersifat unique
     * karena relasinya satu user dengan satu employee.
     */
    //public function employee(): HasOne
    //{
    //    return $this->hasOne(
    //        Employee::class,
    //        'user_id',
    //        'id'
    //    );
    //}

    /*
    |--------------------------------------------------------------------------
    | Query scope berdasarkan status
    |--------------------------------------------------------------------------
    */

    public function scopeActive(Builder $query): Builder
    {
        return $query->where(
            'status',
            self::STATUS_ACTIVE
        );
    }

    public function scopeInactive(Builder $query): Builder
    {
        return $query->where(
            'status',
            self::STATUS_INACTIVE
        );
    }

    public function scopeSuspended(Builder $query): Builder
    {
        return $query->where(
            'status',
            self::STATUS_SUSPENDED
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Query scope berdasarkan role
    |--------------------------------------------------------------------------
    */

    /**
     * Mengambil user berdasarkan satu atau beberapa nama role.
     *
     * Contoh:
     *
     * User::byRole(Role::EXECUTIVE)->get();
     *
     * User::byRole([
     *     Role::SUPER_ADMIN,
     *     Role::EXECUTIVE,
     * ])->get();
     */
    public function scopeByRole(
        Builder $query,
        string | array $roles
    ): Builder {
        $roleNames = is_array($roles)
            ? $roles
            : [$roles];

        return $query->whereHas(
            'role',
            static function (Builder $roleQuery) use ($roleNames): void {
                $roleQuery->whereIn('name', $roleNames);
            }
        );
    }

    /**
     * Mengambil user yang boleh mengakses Dashboard Eksekutif.
     */
    public function scopeCanAccessExecutiveDashboard(
        Builder $query
    ): Builder {
        return $query
            ->where('status', self::STATUS_ACTIVE)
            ->whereHas(
                'role',
                static function (Builder $roleQuery): void {
                    $roleQuery->where('guard_name', 'web')
                        ->whereIn('name', [
                            Role::SUPER_ADMIN,
                            Role::EXECUTIVE,
                        ]);
                }
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Daftar status
    |--------------------------------------------------------------------------
    */

    public static function statuses(): array
    {
        return [
            self::STATUS_ACTIVE,
            self::STATUS_INACTIVE,
            self::STATUS_SUSPENDED,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Pemeriksaan status
    |--------------------------------------------------------------------------
    */

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isInactive(): bool
    {
        return $this->status === self::STATUS_INACTIVE;
    }

    public function isSuspended(): bool
    {
        return $this->status === self::STATUS_SUSPENDED;
    }

    /*
    |--------------------------------------------------------------------------
    | Pemeriksaan role
    |--------------------------------------------------------------------------
    */

    /**
     * Memeriksa apakah user mempunyai salah satu role.
     */
    public function hasRole(string | array $roles): bool
    {
        $allowedRoles = is_array($roles)
            ? $roles
            : [$roles];

        /*
         * Memastikan relasi role tersedia.
         */
        $this->loadMissing('role');

        if ($this->role === null) {
            return false;
        }

        return in_array(
            $this->role->name,
            $allowedRoles,
            true
        );
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole(Role::SUPER_ADMIN);
    }

    public function isExecutive(): bool
    {
        return $this->hasRole(Role::EXECUTIVE);
    }

    public function isHr(): bool
    {
        return $this->hasRole(Role::HR);
    }

    public function isOperational(): bool
    {
        return $this->hasRole(Role::OPERATIONAL);
    }

    public function isFinance(): bool
    {
        return $this->hasRole(Role::FINANCE);
    }

    /*
    |--------------------------------------------------------------------------
    | Pemeriksaan permission
    |--------------------------------------------------------------------------
    */

    /**
     * Memeriksa apakah user mempunyai permission tertentu.
     *
     * Super admin otomatis memiliki seluruh permission.
     */
    public function hasPermission(string $permission): bool
    {
        if (! $this->isActive()) {
            return false;
        }

        if ($this->isSuperAdmin()) {
            return true;
        }

        if ($this->role_id === null) {
            return false;
        }

        $this->loadMissing('role.permissions');

        if ($this->role === null) {
            return false;
        }

        return $this->role->permissions->contains(
            'name',
            $permission
        );
    }

    /**
     * Memeriksa apakah user mempunyai minimal satu permission.
     */
    public function hasAnyPermission(array $permissions): bool
    {
        if (
            ! $this->isActive()
            || $permissions === []
        ) {
            return false;
        }

        if ($this->isSuperAdmin()) {
            return true;
        }

        if ($this->role_id === null) {
            return false;
        }

        $this->loadMissing('role.permissions');

        if ($this->role === null) {
            return false;
        }

        return $this->role->permissions->contains(
            static function (Permission $permission) use ($permissions): bool {
                return in_array(
                    $permission->name,
                    $permissions,
                    true
                );
            }
        );
    }

    /**
     * Memeriksa apakah user mempunyai seluruh permission.
     */
    public function hasAllPermissions(array $permissions): bool
    {
        if (! $this->isActive()) {
            return false;
        }

        if ($permissions === []) {
            return true;
        }

        if ($this->isSuperAdmin()) {
            return true;
        }

        if ($this->role_id === null) {
            return false;
        }

        $this->loadMissing('role.permissions');

        if ($this->role === null) {
            return false;
        }

        $ownedPermissions = $this->role->permissions
            ->pluck('name')
            ->all();

        foreach ($permissions as $permission) {
            if (! in_array($permission, $ownedPermissions, true)) {
                return false;
            }
        }

        return true;
    }

    /*
    |--------------------------------------------------------------------------
    | Hak akses dashboard
    |--------------------------------------------------------------------------
    */

    /**
     * User aktif yang sudah memiliki role dapat membuka
     * dashboard utama.
     */
    public function canAccessDashboard(): bool
    {
        if (! $this->isActive()) {
            return false;
        }

        $this->loadMissing('role');

        return $this->role !== null
        && $this->role->guard_name === 'web';
    }

    /**
     * Dashboard khusus eksekutif.
     */
    public function canAccessExecutiveDashboard(): bool
    {
        if (! $this->isActive()) {
            return false;
        }

        $this->loadMissing('role');

        if (
            $this->role === null
            || $this->role->guard_name !== 'web'
        ) {
            return false;
        }

        return $this->hasRole([
            Role::SUPER_ADMIN,
            Role::EXECUTIVE,
        ]);
    }

    /**
     * Dashboard SDM.
     */
    public function canAccessHrDashboard(): bool
    {
        return $this->isActive()
        && $this->hasRole([
            Role::SUPER_ADMIN,
            Role::HR,
        ]);
    }

    /**
     * Dashboard operasional.
     */
    public function canAccessOperationalDashboard(): bool
    {
        return $this->isActive()
        && $this->hasRole([
            Role::SUPER_ADMIN,
            Role::EXECUTIVE,
            Role::OPERATIONAL,
        ]);
    }

    /**
     * Dashboard keuangan.
     */
    public function canAccessFinanceDashboard(): bool
    {
        return $this->isActive()
        && $this->hasRole([
            Role::SUPER_ADMIN,
            Role::EXECUTIVE,
            Role::FINANCE,
        ]);
    }
}

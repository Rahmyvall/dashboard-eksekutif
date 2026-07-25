<?php

declare (strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | ROLE CONSTANT
    |--------------------------------------------------------------------------
    */

    public const ROLE_SUPER_ADMIN = 'super_admin';

    public const ROLE_DIREKTUR_UTAMA = 'executive';

    public const ROLE_HRD = 'hr';

    public const ROLE_MANAGER_DEPARTEMEN = 'manager_departemen';

    public const ROLE_KARYAWAN = 'karyawan';

    public const ROLE_ADMIN_PELAYANAN = 'admin_pelayanan';

    public const ROLE_ADMIN_OPERASIONAL = 'admin_operasional';

    public const ROLE_KEUANGAN = 'finance';

    public const ROLE_AUDITOR = 'auditor';

    /*
    |--------------------------------------------------------------------------
    | STATUS
    |--------------------------------------------------------------------------
    */

    public const STATUS_ACTIVE = 'active';

    public const STATUS_INACTIVE = 'inactive';

    public const STATUS_SUSPENDED = 'suspended';

    /*
    |--------------------------------------------------------------------------
    | Fillable
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        'name',

        'email',

        'password',

        'status',

        'last_login_at',

        'last_login_ip',

    ];

    protected $hidden = [

        'password',

        'remember_token',

    ];

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

            'email_verified_at' => 'datetime',

            'last_login_at'     => 'datetime',

            /*
             * Gunakan hashed untuk create/update melalui model.
             * SQL langsung tetap harus bcrypt.
             */
            'password'          => 'hashed',

        ];

    }

    /*
    |--------------------------------------------------------------------------
    | Email Mutator
    |--------------------------------------------------------------------------
    */

    protected function email(): Attribute
    {

        return Attribute::make(

            set: fn($value) =>
            strtolower(trim($value))

        );

    }

    /*
    |--------------------------------------------------------------------------
    | Relationship
    |--------------------------------------------------------------------------
    */

    public function roles(): BelongsToMany
    {

        return $this->belongsToMany(

            Role::class,

            'role_user',

            'user_id',

            'role_id'

        )
            ->withTimestamps();

    }

    /*
    |--------------------------------------------------------------------------
    | Status
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
    | ROLE CHECK
    |--------------------------------------------------------------------------
    */

    public function hasRole(
        string | array $roles
    ): bool {

        $roles = is_array($roles)
            ? $roles
            : [$roles];

        return $this->roles()

            ->whereIn(
                'roles.name',
                $roles
            )

            ->exists();

    }

    public function isSuperAdmin(): bool
    {

        return $this->hasRole(
            self::ROLE_SUPER_ADMIN
        );

    }

    public function isDirekturUtama(): bool
    {

        return $this->hasRole(
            self::ROLE_DIREKTUR_UTAMA
        );

    }

    public function isHrd(): bool
    {

        return $this->hasRole(
            self::ROLE_HRD
        );

    }

    public function isAuditor(): bool
    {

        return $this->hasRole(
            self::ROLE_AUDITOR
        );

    }

    /*
    |--------------------------------------------------------------------------
    | ACTIVE ROLE SESSION
    |--------------------------------------------------------------------------
    */

    public function activeRole(): ?Role
    {

        $id = session(
            'active_role_id'
        );

        if (! $id) {
            return null;
        }

        return $this->roles()

            ->where(
                'roles.id',
                $id
            )

            ->first();

    }

    public function activeRoleName(): ?string
    {

        return session(
            'active_role_name'
        );

    }

    public function hasActiveRole(
        string | array $roles
    ): bool {

        return in_array(

            $this->activeRoleName(),

            (array) $roles,

            true

        );

    }

    /*
    |--------------------------------------------------------------------------
    | PERMISSION
    |--------------------------------------------------------------------------
    */

    public function hasPermission(
        string $permission
    ): bool {

        return $this->roles()

            ->whereHas(
                'permissions',
                function ($q) use ($permission) {

                    $q->where(
                        'permissions.name',
                        $permission
                    );

                }
            )

            ->exists();

    }

    /*
    |--------------------------------------------------------------------------
    | Dashboard Access
    |--------------------------------------------------------------------------
    */

    public function canAccessDashboard(): bool
    {

        return $this->isActive()
        && $this->activeRole() != null;

    }

    /*
    |--------------------------------------------------------------------------
    | Scope
    |--------------------------------------------------------------------------
    */

    public function scopeActive(
        Builder $query
    ): Builder {

        return $query->where(
            'status',
            self::STATUS_ACTIVE
        );

    }

    public function scopeByRole(
        Builder $query,
        string | array $roles
    ): Builder {

        return $query->whereHas(

            'roles',

            function ($q) use ($roles) {

                $q->whereIn(

                    'roles.name',

                    (array) $roles

                );

            }

        );

    }

    /*
    |--------------------------------------------------------------------------
    | Login Update
    |--------------------------------------------------------------------------
    */

    public function updateLoginInfo(): void
    {

        $this->update([

            'last_login_at' => now(),

            'last_login_ip' => request()->ip(),

        ]);

    }

    public static function statuses(): array
    {

        return [

            self::STATUS_ACTIVE,

            self::STATUS_INACTIVE,

            self::STATUS_SUSPENDED,

        ];

    }

}

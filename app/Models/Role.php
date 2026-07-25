<?php

declare (strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{

    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | Table
    |--------------------------------------------------------------------------
    */

    protected $table = 'roles';

    /*
    |--------------------------------------------------------------------------
    | Fillable
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        'name',

        'guard_name',

        'description',

    ];

    /*
    |--------------------------------------------------------------------------
    | Cast
    |--------------------------------------------------------------------------
    */

    protected function casts(): array
    {
        return [

            'id'         => 'integer',

            'created_at' => 'datetime',

            'updated_at' => 'datetime',

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | ROLE CONSTANT
    |--------------------------------------------------------------------------
    */

    public const SUPER_ADMIN = 'super_admin';

    public const DIREKTUR_UTAMA = 'executive';

    public const HRD = 'hr';

    public const MANAGER_DEPARTEMEN = 'manager_departemen';

    public const KARYAWAN = 'karyawan';

    public const ADMIN_PELAYANAN = 'admin_pelayanan';

    public const ADMIN_OPERASIONAL = 'admin_operasional';

    public const KEUANGAN = 'finance';

    public const AUDITOR = 'auditor';

    /*
    |--------------------------------------------------------------------------
    | Relationship User
    |--------------------------------------------------------------------------
    |
    | roles
    |   |
    | role_user
    |   |
    | users
    |
    */

    public function users(): BelongsToMany
    {

        return $this->belongsToMany(

            User::class,

            'role_user',

            'role_id',

            'user_id'

        )
            ->withTimestamps();

    }

    /*
    |--------------------------------------------------------------------------
    | Relationship Permission
    |--------------------------------------------------------------------------
    |
    | roles
    |   |
    | permission_role
    |   |
    | permissions
    |
    */

    public function permissions(): BelongsToMany
    {

        return $this->belongsToMany(

            Permission::class,

            'permission_role',

            'role_id',

            'permission_id'

        )
            ->withTimestamps();

    }

    /*
    |--------------------------------------------------------------------------
    | Check Role
    |--------------------------------------------------------------------------
    */

    public function hasName(
        string $role
    ): bool {

        return $this->name === $role;

    }

    public function isSuperAdmin(): bool
    {

        return $this->hasName(
            self::SUPER_ADMIN
        );

    }

    public function isDirekturUtama(): bool
    {

        return $this->hasName(
            self::DIREKTUR_UTAMA
        );

    }

    public function isHrd(): bool
    {

        return $this->hasName(
            self::HRD
        );

    }

    public function isManagerDepartemen(): bool
    {

        return $this->hasName(
            self::MANAGER_DEPARTEMEN
        );

    }

    public function isKaryawan(): bool
    {

        return $this->hasName(
            self::KARYAWAN
        );

    }

    public function isAdminPelayanan(): bool
    {

        return $this->hasName(
            self::ADMIN_PELAYANAN
        );

    }

    public function isAdminOperasional(): bool
    {

        return $this->hasName(
            self::ADMIN_OPERASIONAL
        );

    }

    public function isKeuangan(): bool
    {

        return $this->hasName(
            self::KEUANGAN
        );

    }

    public function isAuditor(): bool
    {

        return $this->hasName(
            self::AUDITOR
        );

    }

    /*
    |--------------------------------------------------------------------------
    | Permission Check
    |--------------------------------------------------------------------------
    */

    public function hasPermission(
        string $permission
    ): bool {

        return $this->permissions()

            ->where(
                'permissions.name',
                $permission
            )

            ->exists();

    }

    /*
    |--------------------------------------------------------------------------
    | Scope
    |--------------------------------------------------------------------------
    */

    public function scopeNamed(
        Builder $query,
        string | array $roles
    ): Builder {

        return $query->whereIn(

            'name',

            (array) $roles

        );

    }

    /*
    |--------------------------------------------------------------------------
    | Static Role List
    |--------------------------------------------------------------------------
    */

    public static function names(): array
    {

        return [

            self::SUPER_ADMIN,

            self::DIREKTUR_UTAMA,

            self::HRD,

            self::MANAGER_DEPARTEMEN,

            self::KARYAWAN,

            self::ADMIN_PELAYANAN,

            self::ADMIN_OPERASIONAL,

            self::KEUANGAN,

            self::AUDITOR,

        ];

    }

    /*
    |--------------------------------------------------------------------------
    | Label Role
    |--------------------------------------------------------------------------
    */

    public function label(): string
    {

        return match ($this->name) {

            self::SUPER_ADMIN        =>
            'Super Admin',

            self::DIREKTUR_UTAMA     =>
            'Direktur Utama',

            self::HRD                =>
            'HRD',

            self::MANAGER_DEPARTEMEN =>
            'Manager Departemen',

            self::KARYAWAN           =>
            'Karyawan',

            self::ADMIN_PELAYANAN    =>
            'Admin Pelayanan',

            self::ADMIN_OPERASIONAL  =>
            'Admin Operasional',

            self::KEUANGAN           =>
            'Keuangan',

            self::AUDITOR            =>
            'Auditor',

            default                  =>
            ucwords(
                str_replace(
                    '_',
                    ' ',
                    $this->name
                )
            ),

        };

    }

}

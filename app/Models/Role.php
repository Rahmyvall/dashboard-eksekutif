<?php

declare (strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | Nama role
    |--------------------------------------------------------------------------
    */

    public const SUPER_ADMIN = 'super_admin';
    public const EXECUTIVE   = 'executive';
    public const HR          = 'hr';
    public const OPERATIONAL = 'operational';
    public const FINANCE     = 'finance';

    /*
    |--------------------------------------------------------------------------
    | Konfigurasi model
    |--------------------------------------------------------------------------
    */

    protected $table = 'roles';

    protected $fillable = [
        'name',
        'guard_name',
        'description',
    ];

    protected $attributes = [
        'guard_name' => 'web',
    ];

    protected function casts(): array
    {
        return [
            'id' => 'integer',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relasi
    |--------------------------------------------------------------------------
    */

    /**
     * User yang menggunakan role ini.
     */
    public function users(): HasMany
    {
        return $this->hasMany(
            User::class,
            'role_id',
            'id'
        );
    }

    /**
     * Permission yang dimiliki role.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            Permission::class,
            'permission_role',
            'role_id',
            'permission_id'
        )->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | Query scope
    |--------------------------------------------------------------------------
    */

    public function scopeNamed(
        Builder $query,
        string | array $roles
    ): Builder {
        $roleNames = is_array($roles)
            ? $roles
            : [$roles];

        return $query->whereIn('name', $roleNames);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public static function names(): array
    {
        return [
            self::SUPER_ADMIN,
            self::EXECUTIVE,
            self::HR,
            self::OPERATIONAL,
            self::FINANCE,
        ];
    }

    public function hasPermission(string $permission): bool
    {
        $this->loadMissing('permissions');

        return $this->permissions->contains(
            'name',
            $permission
        );
    }
}

<?php

declare (strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property int $id
 * @property int|null $role_id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $status
 * @property Role|null $role
 */
class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    /*
    |--------------------------------------------------------------------------
    | Status User
    |--------------------------------------------------------------------------
    */

    public const STATUS_ACTIVE = 'active';

    public const STATUS_INACTIVE = 'inactive';

    public const STATUS_SUSPENDED = 'suspended';

    /*
    |--------------------------------------------------------------------------
    | Mass Assignment
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'role_id',
        'name',
        'email',
        'password',
        'status',
        'last_login_at',
    ];

    /*
    |--------------------------------------------------------------------------
    | Hidden
    |--------------------------------------------------------------------------
    */

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /*
    |--------------------------------------------------------------------------
    | Default Value
    |--------------------------------------------------------------------------
    */

    protected $attributes = [

        'status' => self::STATUS_ACTIVE,

    ];

    /*
    |--------------------------------------------------------------------------
    | Cast
    |--------------------------------------------------------------------------
    */

    protected function casts(): array
    {
        return [

            'role_id'           => 'integer',

            'email_verified_at' => 'datetime',

            'last_login_at'     => 'datetime',

            'password'          => 'hashed',

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Mutator Email
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
    | Relasi Role
    |--------------------------------------------------------------------------
    */

    public function role(): BelongsTo
    {
        return $this->belongsTo(
            Role::class,
            'role_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Status Checking
    |--------------------------------------------------------------------------
    */

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /*
    |--------------------------------------------------------------------------
    | Role Checking
    |--------------------------------------------------------------------------
    */

    public function hasRole(
        string | array $roles
    ): bool {

        $this->loadMissing('role');

        if ($this->role === null) {

            return false;

        }

        $roles = is_array($roles)
            ? $roles
            : [$roles];

        return in_array(
            $this->role->name,
            $roles,
            true
        );

    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole(
            Role::SUPER_ADMIN
        );
    }

    public function canAccessDashboard(): bool
    {

        return $this->isActive()
        && $this->role !== null;

    }

    public function canAccessExecutiveDashboard(): bool
    {

        return $this->hasRole([

            Role::SUPER_ADMIN,

            Role::EXECUTIVE,

        ]);

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

        $roles = is_array($roles)
            ? $roles
            : [$roles];

        return $query->whereHas(
            'role',
            function (Builder $q) use ($roles) {

                $q->whereIn(
                    'name',
                    $roles
                );

            }
        );

    }

}

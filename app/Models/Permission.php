<?php

declare (strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Model Permission.
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 *
 * @property-read Collection<int, Role> $roles
 */
class Permission extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | Konfigurasi Model
    |--------------------------------------------------------------------------
    */

    protected $table = 'permissions';

    protected $fillable = [
        'name',
        'description',
    ];

    /*
    |--------------------------------------------------------------------------
    | Cast Attribute
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
    | Relasi Role
    |--------------------------------------------------------------------------
    |
    | permissions
    |      ↓
    | permission_role
    |      ↓
    | roles
    |
    */

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Role::class,
            'permission_role',
            'permission_id',
            'role_id'
        )->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | Query Scope
    |--------------------------------------------------------------------------
    */

    /**
     * Filter berdasarkan satu atau beberapa nama permission.
     *
     * Contoh:
     *
     * Permission::named('users.view')->get();
     *
     * Permission::named([
     *     'users.view',
     *     'users.create',
     * ])->get();
     */
    public function scopeNamed(
        Builder $query,
        string | array $permissions
    ): Builder {
        $permissionNames = is_array($permissions)
            ? $permissions
            : [$permissions];

        return $query->whereIn(
            'permissions.name',
            $permissionNames
        );
    }

    /**
     * Filter permission berdasarkan nama role.
     *
     * Contoh:
     *
     * Permission::forRole('SUPER_ADMIN')->get();
     */
    public function scopeForRole(
        Builder $query,
        string | array $roles
    ): Builder {
        $roleNames = is_array($roles)
            ? $roles
            : [$roles];

        return $query->whereHas(
            'roles',
            function (Builder $roleQuery) use ($roleNames): void {
                $roleQuery->whereIn(
                    'roles.name',
                    $roleNames
                );
            }
        );
    }

    /**
     * Filter permission berdasarkan ID role.
     */
    public function scopeForRoleId(
        Builder $query,
        int $roleId
    ): Builder {
        return $query->whereHas(
            'roles',
            function (Builder $roleQuery) use ($roleId): void {
                $roleQuery->where(
                    'roles.id',
                    $roleId
                );
            }
        );
    }

    /**
     * Pencarian permission berdasarkan nama atau deskripsi.
     *
     * Menggunakan ILIKE karena database menggunakan PostgreSQL.
     */
    public function scopeSearch(
        Builder $query,
        ?string $keyword
    ): Builder {
        $keyword = trim((string) $keyword);

        if ($keyword === '') {
            return $query;
        }

        return $query->where(
            function (Builder $searchQuery) use ($keyword): void {
                $searchQuery
                    ->where(
                        'permissions.name',
                        'ILIKE',
                        '%' . $keyword . '%'
                    )
                    ->orWhere(
                        'permissions.description',
                        'ILIKE',
                        '%' . $keyword . '%'
                    );
            }
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Permission Helper
    |--------------------------------------------------------------------------
    */

    /**
     * Memeriksa nama permission.
     *
     * Method ini menggunakan nama matchesName(), bukan is(),
     * karena is() sudah digunakan oleh model Eloquent.
     */
    public function matchesName(string $permission): bool
    {
        return $this->name === $permission;
    }

    /**
     * Memeriksa apakah permission termasuk salah satu nama.
     */
    public function isOneOf(
        string | array $permissions
    ): bool {
        $permissionNames = is_array($permissions)
            ? $permissions
            : [$permissions];

        return in_array(
            $this->name,
            $permissionNames,
            true
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Pemeriksaan Role
    |--------------------------------------------------------------------------
    */

    /**
     * Memeriksa apakah permission dimiliki oleh satu atau beberapa role.
     */
    public function hasRole(
        string | array $roles
    ): bool {
        $roleNames = is_array($roles)
            ? $roles
            : [$roles];

        /*
        |--------------------------------------------------------------------------
        | Jika relasi sudah dimuat, gunakan collection
        |--------------------------------------------------------------------------
        */

        if ($this->relationLoaded('roles')) {
            return $this->roles
                ->pluck('name')
                ->intersect($roleNames)
                ->isNotEmpty();
        }

        /*
        |--------------------------------------------------------------------------
        | Jika relasi belum dimuat, gunakan query exists
        |--------------------------------------------------------------------------
        */

        return $this->roles()
            ->whereIn(
                'roles.name',
                $roleNames
            )
            ->exists();
    }

    /**
     * Memeriksa role berdasarkan ID.
     */
    public function hasRoleId(int $roleId): bool
    {
        if ($this->relationLoaded('roles')) {
            return $this->roles->contains(
                function (Role $role) use ($roleId): bool {
                    return (int) $role->id === $roleId;
                }
            );
        }

        return $this->roles()
            ->where(
                'roles.id',
                $roleId
            )
            ->exists();
    }

    /*
    |--------------------------------------------------------------------------
    | Assignment Role
    |--------------------------------------------------------------------------
    */

    /**
     * Memberikan permission kepada satu role.
     *
     * Tidak menghapus relasi role lainnya.
     */
    public function assignToRole(
        int | Role $role
    ): void {
        $roleId = $this->resolveRoleId($role);

        if ($roleId === null) {
            return;
        }

        $this->roles()
            ->syncWithoutDetaching([
                $roleId,
            ]);

        $this->unsetRelation('roles');
    }

    /**
     * Memberikan permission kepada beberapa role.
     *
     * @param array<int, int|Role> $roles
     */
    public function assignToRoles(array $roles): void
    {
        $roleIds = [];

        foreach ($roles as $role) {
            $roleId = $this->resolveRoleId($role);

            if ($roleId !== null) {
                $roleIds[] = $roleId;
            }
        }

        $roleIds = array_values(
            array_unique($roleIds)
        );

        if ($roleIds === []) {
            return;
        }

        $this->roles()
            ->syncWithoutDetaching($roleIds);

        $this->unsetRelation('roles');
    }

    /**
     * Mengganti seluruh role yang memiliki permission ini.
     *
     * Role lama yang tidak terdapat dalam daftar akan dilepas.
     *
     * @param array<int, int|Role> $roles
     */
    public function syncRoles(array $roles): void
    {
        $roleIds = [];

        foreach ($roles as $role) {
            $roleId = $this->resolveRoleId($role);

            if ($roleId !== null) {
                $roleIds[] = $roleId;
            }
        }

        $this->roles()->sync(
            array_values(
                array_unique($roleIds)
            )
        );

        $this->unsetRelation('roles');
    }

    /**
     * Mencabut permission dari satu role.
     */
    public function revokeFromRole(
        int | Role $role
    ): void {
        $roleId = $this->resolveRoleId($role);

        if ($roleId === null) {
            return;
        }

        $this->roles()->detach($roleId);

        $this->unsetRelation('roles');
    }

    /**
     * Mencabut permission dari seluruh role.
     */
    public function revokeFromAllRoles(): void
    {
        $this->roles()->detach();

        $this->unsetRelation('roles');
    }

    /*
    |--------------------------------------------------------------------------
    | Informasi Permission
    |--------------------------------------------------------------------------
    */

    /**
     * Mengubah nama permission menjadi label.
     *
     * users.view menjadi Users View.
     */
    public function label(): string
    {
        return ucwords(
            str_replace(
                [
                    '.',
                    '_',
                    '-',
                ],
                ' ',
                $this->name
            )
        );
    }

    /**
     * Mengambil nama modul permission.
     *
     * users.view menghasilkan users.
     */
    public function moduleName(): string
    {
        $parts = explode('.', $this->name);

        return $parts[0] ?? $this->name;
    }

    /**
     * Mengambil nama aksi permission.
     *
     * users.view menghasilkan view.
     */
    public function actionName(): ?string
    {
        $parts = explode('.', $this->name);

        return $parts[1] ?? null;
    }

    /*
    |--------------------------------------------------------------------------
    | Internal Helper
    |--------------------------------------------------------------------------
    */

    /**
     * Mengubah model Role atau ID role menjadi integer.
     */
    private function resolveRoleId(
        int | Role $role
    ): ?int {
        if ($role instanceof Role) {
            $roleId = $role->getKey();

            return is_numeric($roleId)
                ? (int) $roleId
                : null;
        }

        return $role > 0
            ? $role
            : null;
    }
}

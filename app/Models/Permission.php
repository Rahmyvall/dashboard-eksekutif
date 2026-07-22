<?php

declare (strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    use HasFactory;

    protected $table = 'permissions';

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

    /**
     * Role yang mempunyai permission ini.
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
}

<?php

declare (strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoleUser extends Model
{

    protected $table = 'role_user';

    protected $fillable = [
        'role_id',
        'user_id',
    ];

    /**
     * Relasi ke tabel roles
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
     * Relasi ke tabel users
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id',
            'id'
        );
    }

}

<?php

declare (strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{

    /*
    |--------------------------------------------------------------------------
    | Table
    |--------------------------------------------------------------------------
    */

    protected $table = 'permissions';

    /*
    |--------------------------------------------------------------------------
    | Fillable
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        'name',

        'guard_name',

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
    | Scope Search
    |--------------------------------------------------------------------------
    */

    public function scopeNamed(
        Builder $query,
        string | array $permissions
    ): Builder {

        return $query->whereIn(

            'name',

            (array) $permissions

        );

    }

    /*
    |--------------------------------------------------------------------------
    | Scope Role
    |--------------------------------------------------------------------------
    */

    public function scopeForRole(
        Builder $query,
        string | array $roles
    ): Builder {

        return $query->whereHas(

            'roles',

            function ($query) use ($roles) {

                $query->whereIn(

                    'name',

                    (array) $roles

                );

            }

        );

    }

    /*
    |--------------------------------------------------------------------------
    | Search Permission
    |--------------------------------------------------------------------------
    */

    public function scopeSearch(
        Builder $query,
        ?string $keyword
    ): Builder {

        if (! $keyword) {

            return $query;

        }

        return $query->where(

            'name',

            'ILIKE',

            '%' . $keyword . '%'

        );

    }

    /*
    |--------------------------------------------------------------------------
    | Helper
    |--------------------------------------------------------------------------
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

    public function moduleName(): string
    {

        return explode(

            '.',

            $this->name

        )[0];

    }

    public function actionName(): ?string
    {

        return explode(

            '.',

            $this->name

        )[1] ?? null;

    }

}

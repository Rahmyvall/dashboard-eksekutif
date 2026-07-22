<?php

declare (strict_types = 1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{

    public function handle(
        Request $request,
        Closure $next,
        string ...$roles
    ): Response {

        $user = Auth::user();

        if ($user === null) {

            abort(401);

        }

        /** @var \App\Models\User $user */
        if (
            ! $user->hasRole($roles)
        ) {

            abort(
                403,
                'Akses ditolak.'
            );

        }

        return $next($request);

    }

}
<?php

declare (strict_types = 1);

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle incoming request.
     */
    public function handle(
        Request $request,
        Closure $next,
        string ...$roles
    ): Response {

        /**
         * Ambil user login
         *
         * @var User|null $user
         */
        $user = Auth::user();

        /**
         * Jika user belum login
         */
        if ($user === null) {

            abort(
                401,
                'Anda harus login terlebih dahulu.'
            );

        }

        /**
         * Validasi role user
         *
         * Contoh:
         * role:super_admin
         * role:super_admin,direktur_utama
         */
        if (! $user->hasAnyRole($roles)) {

            abort(
                403,
                'Anda tidak memiliki akses ke halaman ini.'
            );

        }

        return $next($request);

    }
}

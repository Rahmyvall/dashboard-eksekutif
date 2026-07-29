<?php

declare (strict_types = 1);

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Memastikan pengguna memiliki setidaknya satu role yang diizinkan.
     *
     * Mendukung:
     * role:super_admin
     * role:super_admin,direktur_utama
     * role:super_admin|direktur_utama
     */
    public function handle(
        Request $request,
        Closure $next,
        string ...$roles
    ): Response {
        /** @var User|null $user */
        $user = $request->user();

        abort_if(
            $user === null,
            401,
            'Anda harus login terlebih dahulu.'
        );

        $allowedRoles = collect($roles)
            ->flatMap(static function (string $role): array {
                return preg_split('/[|,]/', $role) ?: [];
            })
            ->map(static fn(string $role): string => trim($role))
            ->filter(static fn(string $role): bool => $role !== '')
            ->unique()
            ->values()
            ->all();

        abort_if(
            $allowedRoles === [],
            403,
            'Role yang diizinkan belum dikonfigurasi.'
        );

        abort_unless(
            $user->hasAnyRole($allowedRoles),
            403,
            'Anda tidak memiliki akses ke halaman ini.'
        );

        return $next($request);
    }
}

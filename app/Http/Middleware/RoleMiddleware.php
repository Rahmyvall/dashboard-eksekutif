<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    private array $roleAliases = [
        'super_admin' => ['super_admin', 'super administrator', 'superadministrator'],
        'direktur_utama' => ['direktur_utama', 'direktur manager', 'direktur_manager', 'direktur utama', 'direkturutama', 'executive'],
        'hrd_manager' => ['hrd_manager', 'hrd manager', 'hrdmanager'],
        'manager_departemen' => ['manager_departemen', 'manager departemen', 'managerdepartemen'],
        'karyawan' => ['karyawan'],
        'admin_pelayanan' => ['admin_pelayanan', 'admin pelayanan'],
        'admin_operasional' => ['admin_operasional', 'admin operasional'],
        'finance_staff' => ['finance_staff', 'finance staff'],
        'auditor_internal' => ['auditor_internal', 'auditor internal'],
    ];

    public function handle(
        Request $request,
        Closure $next,
        string ...$roles
    ): Response {
        $user = $request->user();

        abort_if(! $user, 401, 'Anda harus login terlebih dahulu.');

        $allowedRoles = collect($roles)
            ->flatMap(function (string $role): array {
                return preg_split('/[|,]/', $role) ?: [];
            })
            ->map(fn(string $role): string => $this->normalize($role))
            ->filter()
            ->values()
            ->toArray();

        abort_if(empty($allowedRoles), 403, 'Role belum dikonfigurasi.');

        if (in_array('all', $allowedRoles, true) || in_array('*', $allowedRoles, true)) {
            return $next($request);
        }

        $userRoles = $user->getRoleNames()
            ->map(fn(string $role): string => $this->normalize($role))
            ->toArray();

        foreach ($allowedRoles as $allowedRole) {
            $aliases = $this->roleAliases[$allowedRole] ?? [$allowedRole];
            $normalizedAliases = array_map(
                fn(string $alias): string => $this->normalize($alias),
                $aliases
            );

            if (array_intersect($userRoles, $normalizedAliases) !== []) {
                return $next($request);
            }
        }

        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }

    private function normalize(string $role): string
    {
        return strtolower(str_replace(['-', ' '], '_', trim($role)));
    }
}

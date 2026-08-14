<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnforceReadOnlyRoles
{
    /**
     * Aliases role yang wajib read-only.
     *
     * @var array<string, array<int, string>>
     */
    private const READ_ONLY_ROLE_ALIASES = [
        User::ROLE_DIREKTUR_UTAMA => ['direktur_utama', 'direktur utama', 'direkturutama', 'executive'],
        User::ROLE_MANAGER_DEPARTEMEN => ['manager_departemen', 'manager departemen', 'managerdepartemen'],
    ];

    /**
     * Role yang bukan read-only, dipakai untuk mencegah false positive
     * pada user multi-role ketika active role di session belum sinkron.
     *
     * @var array<int, string>
     */
    private const NON_READ_ONLY_ROLES = [
        User::ROLE_SUPER_ADMIN,
        User::ROLE_HRD,
        User::ROLE_KARYAWAN,
        User::ROLE_ADMIN_PELAYANAN,
        User::ROLE_ADMIN_OPERASIONAL,
        User::ROLE_FINANCE_STAFF,
        User::ROLE_AUDITOR,
    ];

    public function handle(Request $request, Closure $next): Response
    {
        if ($this->isLogoutRequest($request)) {
            return $next($request);
        }

        if ($request->isMethodSafe()) {
            return $next($request);
        }

        $user = $request->user();

        if (! $user) {
            return $next($request);
        }

        if (! $this->isReadOnlyRoleContext($request, $user)) {
            return $next($request);
        }

        abort(403, 'Role Direktur dan Manager hanya memiliki akses read-only.');
    }

    private function isLogoutRequest(Request $request): bool
    {
        if ($request->routeIs('logout')) {
            return true;
        }

        return $request->is('logout') || $request->is('api/logout');
    }

    private function isReadOnlyRoleContext(Request $request, User $user): bool
    {
        $activeRole = '';

        if ($request->hasSession()) {
            $activeRole = $this->normalizeRole((string) ($request->session()->get('active_role_name') ?? ''));
        }

        if ($activeRole !== '' && $this->matchesReadOnlyAlias($activeRole)) {
            return true;
        }

        if ($activeRole !== '') {
            return false;
        }

        if ($user->hasAnyRole(self::NON_READ_ONLY_ROLES)) {
            return false;
        }

        return $user->hasAnyRole([
            User::ROLE_DIREKTUR_UTAMA,
            User::ROLE_MANAGER_DEPARTEMEN,
        ]);
    }

    private function matchesReadOnlyAlias(string $normalizedRole): bool
    {
        foreach (self::READ_ONLY_ROLE_ALIASES as $aliases) {
            foreach ($aliases as $alias) {
                if ($this->normalizeRole($alias) === $normalizedRole) {
                    return true;
                }
            }
        }

        return false;
    }

    private function normalizeRole(string $role): string
    {
        return strtolower(str_replace(['-', ' '], '_', trim($role)));
    }
}

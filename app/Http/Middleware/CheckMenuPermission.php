<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMenuPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda harus login terlebih dahulu.',
            ], 401);
        }

        // If no permissions specified, allow access
        if (empty($permissions)) {
            return $next($request);
        }

        if ($this->userHasAnyPermission($user, $permissions)) {
            return $next($request);
        }

        // User doesn't have required permission
        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak memiliki akses ke fitur ini.',
            ], 403);
        }

        abort(403, 'Anda tidak memiliki akses ke fitur ini.');
    }

    /**
     * Cek permission user dengan dua jalur:
     * 1) Gate/Policy via $user->can()
     * 2) Relasi roles -> permissions untuk kompatibilitas model role custom.
     */
    private function userHasAnyPermission(object $user, array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if (method_exists($user, 'can') && $user->can($permission)) {
                return true;
            }
        }

        if (! method_exists($user, 'roles')) {
            return false;
        }

        try {
            return $user->roles()
                ->whereHas('permissions', function ($query) use ($permissions): void {
                    $query->whereIn('permissions.name', $permissions);
                })
                ->exists();
        } catch (\Throwable) {
            return false;
        }
    }
}

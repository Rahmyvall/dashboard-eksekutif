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

        $activeRoleName = (string) (
            session('active_role_name')
            ?? data_get($user, 'active_role_name')
            ?? data_get($user, 'role_name')
            ?? data_get($user, 'role')
            ?? ''
        );

        $normalizedActiveRole = strtolower(str_replace(['-', ' '], '_', trim($activeRoleName)));

        $directeurPermissionFallback = [
            'dashboard.view',
            'dashboard.executive.view',
            'branches.view',
            'departments.view',
            'positions.view',
            'employees.view',
            'customers.view',
            'services.view',
            'service_categories.view',
            'service_orders.view',
            'service_order_items.view',
            'service_order_status.view',
            'work_schedules.view',
            'employee_schedules.view',
            'employee_activities.view',
            'attendances.view',
            'leave_requests.view',
            'performance_indicators.view',
            'performance_periods.view',
            'performance_roles.view',
            'employee_targets.view',
            'employee_performance.view',
            'performance_details.view',
            'customer_feedback.view',
            'customer_complaints.view',
            'invoices.view',
            'payments.view',
            'expenses.view',
            'reports.view',
            'reports.export',
            'reports.services',
            'reports.performance',
            'reports.customers',
            'reports.complaints',
            'reports.finance',
            'reports.hr',
        ];

        if (in_array($normalizedActiveRole, ['direktur_utama', 'direktur_manager', 'direktur manager'], true)) {
            foreach ($permissions as $permission) {
                if (in_array($permission, $directeurPermissionFallback, true)) {
                    return true;
                }
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

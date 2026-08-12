<?php
namespace App\Providers;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{

    private const ROLE_ALIASES = [
        'super_admin' => ['super_admin', 'super administrator', 'superadministrator'],
        'direktur_utama' => ['direktur_utama', 'direktur utama', 'direkturutama', 'executive'],
        'hrd_manager' => ['hrd_manager', 'hrd manager', 'hrdmanager', 'hr'],
        'manager_departemen' => ['manager_departemen', 'manager departemen', 'managerdepartemen'],
        'karyawan' => ['karyawan', 'pegawai', 'employee'],
        'admin_pelayanan' => ['admin_pelayanan', 'admin pelayanan'],
        'admin_operasional' => ['admin_operasional', 'admin operasional'],
        'finance_staff' => ['finance_staff', 'finance staff', 'finance'],
        'auditor_internal' => ['auditor_internal', 'auditor internal', 'auditor'],
    ];

    public function register(): void
    {
        //
    }

    public function boot(): void
    {

        /*
        |--------------------------------------------------------------------------
        | Pagination Style
        |--------------------------------------------------------------------------
        */

        Paginator::useBootstrapFive();

        Gate::define('branch.viewAny', function (User $user): bool {
            return $this->userHasAnyRole($user, [
                'super_admin',
                'direktur_utama',
                'hrd_manager',
                'manager_departemen',
                'karyawan',
                'admin_pelayanan',
                'admin_operasional',
                'finance_staff',
                'auditor_internal',
            ]);
        });

        Gate::define('branch.view', function (User $user, Branch $branch): bool {
            return $this->userHasAnyRole($user, [
                'super_admin',
                'direktur_utama',
                'hrd_manager',
                'manager_departemen',
                'karyawan',
                'admin_pelayanan',
                'admin_operasional',
                'finance_staff',
                'auditor_internal',
            ]);
        });

        Gate::define('branch.create', function (User $user): bool {
            return $this->userHasAnyRole($user, ['super_admin']);
        });

        Gate::define('branch.manage', function (User $user, Branch $branch): bool {
            return $this->userHasAnyRole($user, ['super_admin']);
        });

        Gate::define('branch.approve', function (User $user, Branch $branch): bool {
            return $this->userHasAnyRole($user, ['super_admin']);
        });

        Gate::define('branch.trash', function (User $user): bool {
            return $this->userHasAnyRole($user, ['super_admin']);
        });

    }

    private function userHasAnyRole(User $user, array $roles): bool
    {
        $userRoles = $user->getRoleNames()
            ->map(fn(string $role): string => $this->normalizeRole($role))
            ->all();

        foreach ($roles as $role) {
            $normalizedRole = $this->normalizeRole($role);
            $aliases = self::ROLE_ALIASES[$normalizedRole] ?? [$normalizedRole];
            $normalizedAliases = array_map(
                fn(string $alias): string => $this->normalizeRole($alias),
                $aliases
            );

            if (array_intersect($userRoles, $normalizedAliases) !== []) {
                return true;
            }
        }

        return false;
    }

    private function normalizeRole(string $role): string
    {
        return Str::of($role)
            ->lower()
            ->replace(['-', ' '], '_')
            ->trim()
            ->toString();
    }

}

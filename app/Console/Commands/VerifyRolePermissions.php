<?php

namespace App\Console\Commands;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Console\Command;

class VerifyRolePermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'verify:role-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verify role permissions configuration';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('=== Verifying Role & Permission Configuration ===\n');

        // 1. Check Permissions
        $permissionCount = Permission::count();
        $this->info("✓ Total Permissions: {$permissionCount}");

        // 2. Check Roles
        $roles = Role::all();
        $this->info("✓ Total Roles: {$roles->count()}\n");

        // 3. Show role names
        $this->line('Roles:');
        foreach ($roles as $role) {
            $permCount = $role->permissions()->count();
            $this->line("  - {$role->name} ({$permCount} permissions)");
        }

        // 4. Detail check
        $this->newLine();
        $this->info('=== Detailed Role Permission Breakdown ===\n');

        foreach ($roles as $role) {
            $permissions = $role->permissions()->pluck('name')->toArray();

            if (empty($permissions)) {
                $this->warn("✗ {$role->name}: No permissions assigned");
                continue;
            }

            $this->info("{$role->name}:");
            $this->line("  Permissions: " . count($permissions));

            // Group by feature
            $grouped = [];
            foreach ($permissions as $perm) {
                $parts = explode('.', $perm);
                $feature = $parts[0] ?? 'unknown';
                if (!isset($grouped[$feature])) {
                    $grouped[$feature] = [];
                }
                $grouped[$feature][] = $perm;
            }

            foreach ($grouped as $feature => $perms) {
                $this->line("    - {$feature}: " . implode(', ', $perms));
            }

            $this->newLine();
        }

        // 5. Check critical permissions
        $this->info('=== Checking Critical Permissions ===\n');

        $criticalPerms = [
            'dashboard.view',
            'employees.view',
            'service_orders.view',
            'invoices.view',
            'audit_logs.view',
        ];

        foreach ($criticalPerms as $perm) {
            $exists = Permission::where('name', $perm)->exists();
            $status = $exists ? '✓' : '✗';
            $this->line("{$status} {$perm}");
        }

        $this->newLine();
        $this->info('=== Verification Complete ===');

        return self::SUCCESS;
    }
}

<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\View;
use Tests\TestCase;

class FinanceStaffMenuAccessTest extends TestCase
{
    public function test_finance_staff_sees_required_finance_menu_items(): void
    {
        $user = new class extends User {
            public function getRoleNames(): \Illuminate\Support\Collection
            {
                return collect(['Finance Staff']);
            }

            public function hasRole(string|array $roles): bool
            {
                $roleNames = ['finance_staff', 'finance staff', 'finance'];
                $inputRoles = is_array($roles) ? $roles : [$roles];

                foreach ($inputRoles as $role) {
                    if (in_array(strtolower(str_replace(['-', ' '], '_', trim((string) $role))), $roleNames, true)) {
                        return true;
                    }
                }

                return false;
            }

            public function can($ability, $arguments = []): bool
            {
                return false;
            }
        };

        $this->actingAs($user);

        $html = View::make('layouts.sidebar')->render();

        $this->assertStringContainsString('Keuangan Layanan', $html);
        $this->assertStringContainsString('Laporan Keuangan', $html);
    }

    public function test_direktur_manager_role_is_normalized_to_direktur_utama(): void
    {
        $user = new class extends User {
            public function getRoleNames(): \Illuminate\Support\Collection
            {
                return collect(['Direktur Manager']);
            }

            public function hasRole(string|array $roles): bool
            {
                $roleNames = ['direktur_utama', 'direktur_manager', 'direktur manager', 'direkturutama', 'executive'];
                $inputRoles = is_array($roles) ? $roles : [$roles];

                foreach ($inputRoles as $role) {
                    $normalized = strtolower(str_replace(['-', ' '], '_', trim((string) $role)));
                    if (in_array($normalized, $roleNames, true)) {
                        return true;
                    }
                }

                return false;
            }

            public function can($ability, $arguments = []): bool
            {
                return true;
            }
        };

        $this->actingAs($user);

        $html = View::make('layouts.sidebar')->render();

        $this->assertStringContainsString('Direktur Utama', $html);
        $this->assertStringContainsString('Master Data', $html);
    }
}

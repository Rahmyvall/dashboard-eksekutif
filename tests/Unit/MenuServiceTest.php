<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\MenuService;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Tests\TestCase;

class MenuServiceTest extends TestCase
{
    public function test_super_admin_sees_positions_menu_item(): void
    {
        $user = Mockery::mock(User::class)->makePartial();
        $user->shouldReceive('hasRole')
            ->with('super_admin')
            ->andReturn(true);
        $user->shouldReceive('hasAnyRole')
            ->withArgs(function (array $roles): bool {
                return in_array('super_admin', $roles, true);
            })
            ->andReturn(true);
        $user->shouldReceive('can')->andReturn(true);

        Auth::login($user);

        $menus = app(MenuService::class)->getMenus();

        $positionsVisible = collect($menus)
            ->flatMap(fn ($menu) => $menu['children'] ?? [])
            ->contains(fn ($child) => ($child['label'] ?? '') === 'Data Jabatan');

        $this->assertTrue($positionsVisible);
    }

    public function test_auditor_internal_sees_required_performance_menu_items(): void
    {
        $user = Mockery::mock(User::class)->makePartial();
        $user->shouldReceive('hasRole')
            ->with('auditor_internal')
            ->andReturn(true);
        $user->shouldReceive('hasAnyRole')
            ->withArgs(function (array $roles): bool {
                return in_array('auditor_internal', $roles, true);
            })
            ->andReturn(true);
        $user->shouldReceive('can')->andReturn(true);

        Auth::login($user);

        $menus = app(MenuService::class)->getMenus();

        $visibleLabels = collect($menus)
            ->flatMap(fn ($menu) => $menu['children'] ?? [])
            ->pluck('label')
            ->all();

        $this->assertContains('Indikator Kinerja', $visibleLabels);
        $this->assertContains('Periode Penilaian', $visibleLabels);
        $this->assertContains('Laporan Kinerja', $visibleLabels);
    }
}

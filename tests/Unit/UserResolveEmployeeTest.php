<?php

namespace Tests\Unit;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserResolveEmployeeTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_resolves_employee_from_users_employee_id(): void
    {
        $user = User::factory()->create();
        $department = Department::factory()->create();
        $position = Position::query()->create([
            'department_id' => $department->getKey(),
            'code' => 'POS-TST-001',
            'name' => 'Tester Position 1',
            'level' => 1,
            'status' => 'active',
        ]);
        $employee = Employee::query()->create([
            'department_id' => $department->getKey(),
            'position_id' => $position->getKey(),
            'employee_number' => 'EMP-TST-001',
            'full_name' => 'Tester Karyawan',
            'gender' => 'male',
            'hire_date' => '2026-01-01',
            'employment_status' => 'permanent',
            'basic_salary' => 0,
            'status' => 'active',
        ]);

        $user->forceFill(['employee_id' => $employee->getKey()])->save();

        $this->assertSame($employee->getKey(), $user->fresh()->resolveEmployee()?->getKey());
    }

    public function test_it_falls_back_to_legacy_employees_user_id_link(): void
    {
        $user = User::factory()->create();
        $department = Department::factory()->create();
        $position = Position::query()->create([
            'department_id' => $department->getKey(),
            'code' => 'POS-TST-002',
            'name' => 'Tester Position 2',
            'level' => 1,
            'status' => 'active',
        ]);
        $employee = Employee::query()->create([
            'user_id' => $user->getKey(),
            'department_id' => $department->getKey(),
            'position_id' => $position->getKey(),
            'employee_number' => 'EMP-TST-002',
            'full_name' => 'Tester Legacy',
            'gender' => 'female',
            'hire_date' => '2026-01-01',
            'employment_status' => 'contract',
            'basic_salary' => 0,
            'status' => 'active',
        ]);

        $this->assertSame($employee->getKey(), $user->fresh()->resolveEmployee()?->getKey());
    }
}
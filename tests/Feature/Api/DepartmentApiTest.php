<?php

declare (strict_types = 1);

namespace Tests\Feature\Api;

use App\Models\Department;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DepartmentApiTest extends TestCase
{
    use RefreshDatabase;

    private const BASE_URL = '/api/departments';

    /**
     * Daftar department dapat ditampilkan dengan pagination.
     */
    public function test_can_get_paginated_department_list(): void
    {
        Department::factory()->count(15)->create();

        $response = $this->getJson(
            self::BASE_URL . '?per_page=10&sort_by=name&sort_direction=asc'
        );

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath(
                'message',
                'Daftar department berhasil diambil.'
            )
            ->assertJsonCount(10, 'data')
            ->assertJsonPath('meta.total', 15)
            ->assertJsonPath('meta.per_page', 10)
            ->assertJsonStructure([
                'success',
                'message',
                'data'  => [
                    '*' => $this->resourceStructure(),
                ],
                'links' => [
                    'first',
                    'last',
                    'prev',
                    'next',
                ],
                'meta'  => [
                    'current_page',
                    'from',
                    'last_page',
                    'path',
                    'per_page',
                    'to',
                    'total',
                ],
            ]);
    }

    /**
     * Daftar department dapat dicari dan difilter berdasarkan status.
     */
    public function test_can_search_and_filter_departments(): void
    {
        $matchedDepartment = Department::factory()
            ->active()
            ->create([
                'code'        => 'IT',
                'name'        => 'Information Technology',
                'description' => 'Mengelola layanan teknologi perusahaan.',
            ]);

        Department::factory()
            ->inactive()
            ->create([
                'code' => 'HRD',
                'name' => 'Human Resources',
            ]);

        $response = $this->getJson(
            self::BASE_URL . '?search=technology&status=active'
        );

        $response
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $matchedDepartment->id)
            ->assertJsonPath('data.0.code', 'IT')
            ->assertJsonPath('data.0.status', 'active');
    }

    /**
     * Department baru dapat disimpan.
     */
    public function test_can_create_department(): void
    {
        $payload = [
            'code'        => 'it-support',
            'name'        => 'IT Support',
            'description' => 'Memberikan dukungan teknis internal.',
            'status'      => 'active',
        ];

        $response = $this->postJson(self::BASE_URL, $payload);

        $response
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath(
                'message',
                'Department berhasil ditambahkan.'
            )
            ->assertJsonPath('data.code', 'IT-SUPPORT')
            ->assertJsonPath('data.name', 'IT Support')
            ->assertJsonPath('data.status', 'active')
            ->assertJsonPath('data.status_label', 'Aktif')
            ->assertJsonPath('data.is_active', true)
            ->assertJsonPath('data.is_deleted', false)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => $this->resourceStructure(),
            ]);

        $this->assertDatabaseHas('departments', [
            'code'       => 'IT-SUPPORT',
            'name'       => 'IT Support',
            'status'     => 'active',
            'deleted_at' => null,
        ]);
    }

    /**
     * Validasi store menolak kode yang sudah digunakan.
     */
    public function test_create_department_rejects_duplicate_code(): void
    {
        Department::factory()->create([
            'code' => 'FIN',
        ]);

        $response = $this->postJson(self::BASE_URL, [
            'code'        => 'fin',
            'name'        => 'Finance Duplicate',
            'description' => null,
            'status'      => 'active',
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['code']);

        $this->assertDatabaseCount('departments', 1);
    }

    /**
     * Detail department dapat ditampilkan.
     */
    public function test_can_show_department(): void
    {
        $department = Department::factory()->active()->create();

        $response = $this->getJson(
            self::BASE_URL . '/' . $department->id
        );

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath(
                'message',
                'Detail department berhasil diambil.'
            )
            ->assertJsonPath('data.id', $department->id)
            ->assertJsonPath('data.code', $department->code)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => $this->resourceStructure(),
            ]);
    }

    /**
     * Department dapat diperbarui penuh dengan PUT.
     */
    public function test_can_update_department_using_put(): void
    {
        $department = Department::factory()->create([
            'code'   => 'OLD',
            'name'   => 'Old Department',
            'status' => 'inactive',
        ]);

        $response = $this->putJson(
            self::BASE_URL . '/' . $department->id,
            [
                'code'        => 'new-code',
                'name'        => 'New Department',
                'description' => 'Deskripsi department yang diperbarui.',
                'status'      => 'active',
            ]
        );

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.code', 'NEW-CODE')
            ->assertJsonPath('data.name', 'New Department')
            ->assertJsonPath('data.status', 'active');

        $this->assertDatabaseHas('departments', [
            'id'     => $department->id,
            'code'   => 'NEW-CODE',
            'name'   => 'New Department',
            'status' => 'active',
        ]);
    }

    /**
     * Department dapat diperbarui sebagian dengan PATCH.
     */
    public function test_can_partially_update_department_using_patch(): void
    {
        $department = Department::factory()->active()->create([
            'code' => 'OPS',
            'name' => 'Operations',
        ]);

        $response = $this->patchJson(
            self::BASE_URL . '/' . $department->id,
            [
                'name' => 'Business Operations',
            ]
        );

        $response
            ->assertOk()
            ->assertJsonPath('data.code', 'OPS')
            ->assertJsonPath('data.name', 'Business Operations')
            ->assertJsonPath('data.status', 'active');

        $this->assertDatabaseHas('departments', [
            'id'     => $department->id,
            'code'   => 'OPS',
            'name'   => 'Business Operations',
            'status' => 'active',
        ]);
    }

    /**
     * PATCH tanpa field yang valid ditolak.
     */
    public function test_patch_department_requires_at_least_one_updatable_field(): void
    {
        $department = Department::factory()->create();

        $response = $this->patchJson(
            self::BASE_URL . '/' . $department->id,
            []
        );

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['request']);
    }

    /**
     * Status department dapat diperbarui melalui endpoint khusus.
     */
    public function test_can_update_department_status(): void
    {
        $department = Department::factory()->active()->create();

        $response = $this->patchJson(
            self::BASE_URL . '/' . $department->id . '/status',
            [
                'status' => 'inactive',
            ]
        );

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.status', 'inactive')
            ->assertJsonPath('data.status_label', 'Tidak Aktif')
            ->assertJsonPath('data.is_active', false);

        $this->assertDatabaseHas('departments', [
            'id'     => $department->id,
            'status' => 'inactive',
        ]);
    }

    /**
     * Department dapat dipindahkan ke trash dengan soft delete.
     */
    public function test_can_soft_delete_department(): void
    {
        $department = Department::factory()->create();

        $response = $this->deleteJson(
            self::BASE_URL . '/' . $department->id
        );

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath(
                'message',
                'Department berhasil dipindahkan ke trash.'
            )
            ->assertJsonPath('data.id', $department->id)
            ->assertJsonPath('data.is_deleted', true);

        $this->assertSoftDeleted('departments', [
            'id' => $department->id,
        ]);
    }

    /**
     * Daftar trash hanya menampilkan department yang terhapus.
     */
    public function test_can_get_department_trash(): void
    {
        $trashedDepartment = Department::factory()
            ->trashed()
            ->create([
                'code' => 'TRASHED',
            ]);

        Department::factory()->create([
            'code' => 'AVAILABLE',
        ]);

        $response = $this->getJson(
            self::BASE_URL . '/trash?search=trashed'
        );

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $trashedDepartment->id)
            ->assertJsonPath('data.0.is_deleted', true);
    }

    /**
     * Department yang terhapus dapat dipulihkan.
     */
    public function test_can_restore_soft_deleted_department(): void
    {
        $department = Department::factory()->trashed()->create();

        $response = $this->patchJson(
            self::BASE_URL . '/' . $department->id . '/restore'
        );

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath(
                'message',
                'Department berhasil dipulihkan.'
            )
            ->assertJsonPath('data.id', $department->id)
            ->assertJsonPath('data.is_deleted', false)
            ->assertJsonPath('data.deleted_at', null);

        $this->assertDatabaseHas('departments', [
            'id'         => $department->id,
            'deleted_at' => null,
        ]);
    }

    /**
     * Department di trash dapat dihapus permanen.
     */
    public function test_can_force_delete_department(): void
    {
        $department = Department::factory()->trashed()->create([
            'code' => 'PERMANENT',
        ]);

        $response = $this->deleteJson(
            self::BASE_URL . '/' . $department->id . '/force-delete'
        );

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath(
                'message',
                'Department berhasil dihapus secara permanen.'
            )
            ->assertJsonPath('data.id', $department->id)
            ->assertJsonPath('data.code', 'PERMANENT');

        $this->assertDatabaseMissing('departments', [
            'id' => $department->id,
        ]);
    }

    /**
     * Statistik department menghasilkan jumlah yang tepat.
     */
    public function test_can_get_department_statistics(): void
    {
        Department::factory()->active()->count(2)->create();
        Department::factory()->inactive()->create();
        Department::factory()->active()->trashed()->create();

        $response = $this->getJson(
            self::BASE_URL . '/statistics'
        );

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.total', 3)
            ->assertJsonPath('data.active', 2)
            ->assertJsonPath('data.inactive', 1)
            ->assertJsonPath('data.trashed', 1)
            ->assertJsonPath('data.including_trashed', 4);
    }

    /**
     * Struktur field standar DepartmentResource.
     *
     * @return array<int, string>
     */
    private function resourceStructure(): array
    {
        return [
            'id',
            'code',
            'name',
            'description',
            'status',
            'status_label',
            'is_active',
            'is_deleted',
            'created_at',
            'updated_at',
            'deleted_at',
        ];
    }
}

<?php

declare (strict_types = 1);

namespace Tests\Feature\Api\V1;

use App\Models\ServiceCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceCategoryApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Sequence untuk membuat code unik selama proses testing.
     */
    private static int $sequence = 1;

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function test_can_get_all_service_categories(): void
    {
        $this->createServiceCategory([
            'code' => 'SVC-001',
            'name' => 'Layanan Konsultasi',
        ]);

        $this->createServiceCategory([
            'code' => 'SVC-002',
            'name' => 'Layanan Instalasi',
        ]);

        $response = $this->getJson(
            '/api/v1/service-categories'
        );

        $response
            ->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Data kategori layanan berhasil diambil.',
            ])
            ->assertJsonStructure([
                'success',
                'message',

                'data'  => [
                    '*' => [
                        'id',
                        'code',
                        'name',
                        'description',
                        'status',
                    ],
                ],

                'meta'  => [
                    'current_page',
                    'from',
                    'last_page',
                    'per_page',
                    'to',
                    'total',
                ],

                'links' => [
                    'first',
                    'last',
                    'prev',
                    'next',
                ],
            ]);

        $this->assertSame(
            2,
            $response->json('meta.total')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX - SEARCH
    |--------------------------------------------------------------------------
    */

    public function test_can_search_service_categories(): void
    {
        $this->createServiceCategory([
            'code' => 'SVC-CONSULT',
            'name' => 'Layanan Konsultasi',
        ]);

        $this->createServiceCategory([
            'code' => 'SVC-INSTALL',
            'name' => 'Layanan Instalasi',
        ]);

        $response = $this->getJson(
            '/api/v1/service-categories?search=Konsultasi'
        );

        $response
            ->assertOk()
            ->assertJsonPath(
                'success',
                true
            )
            ->assertJsonPath(
                'meta.total',
                1
            )
            ->assertJsonPath(
                'data.0.code',
                'SVC-CONSULT'
            )
            ->assertJsonPath(
                'data.0.name',
                'Layanan Konsultasi'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX - FILTER STATUS
    |--------------------------------------------------------------------------
    */

    public function test_can_filter_service_categories_by_status(): void
    {
        $this->createServiceCategory([
            'code'   => 'SVC-ACTIVE',
            'status' => 'active',
        ]);

        $this->createServiceCategory([
            'code'   => 'SVC-INACTIVE',
            'status' => 'inactive',
        ]);

        $response = $this->getJson(
            '/api/v1/service-categories?status=active'
        );

        $response
            ->assertOk()
            ->assertJsonPath(
                'meta.total',
                1
            )
            ->assertJsonPath(
                'data.0.code',
                'SVC-ACTIVE'
            )
            ->assertJsonPath(
                'data.0.status',
                'active'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function test_can_create_service_category(): void
    {
        $payload = [
            'code'        => 'svc-001',
            'name'        => 'Layanan Konsultasi',
            'description' => 'Kategori layanan konsultasi pelanggan.',
            'status'      => 'active',
        ];

        $response = $this->postJson(
            '/api/v1/service-categories',
            $payload
        );

        $response
            ->assertCreated()
            ->assertJsonPath(
                'success',
                true
            )
            ->assertJsonPath(
                'message',
                'Kategori layanan berhasil ditambahkan.'
            )
            ->assertJsonPath(
                'data.code',
                'SVC-001'
            )
            ->assertJsonPath(
                'data.name',
                'Layanan Konsultasi'
            )
            ->assertJsonPath(
                'data.description',
                'Kategori layanan konsultasi pelanggan.'
            )
            ->assertJsonPath(
                'data.status',
                'active'
            )
            ->assertJsonPath(
                'data.status_label',
                'Aktif'
            )
            ->assertJsonPath(
                'data.is_active',
                true
            );

        $this->assertDatabaseHas(
            'service_categories',
            [
                'code'        => 'SVC-001',
                'name'        => 'Layanan Konsultasi',
                'description' => 'Kategori layanan konsultasi pelanggan.',
                'status'      => 'active',
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STORE - DEFAULT STATUS
    |--------------------------------------------------------------------------
    */

    public function test_store_uses_active_as_default_status_when_status_not_sent(): void
    {
        $response = $this->postJson(
            '/api/v1/service-categories',
            [
                'code'        => 'SVC-DEFAULT',
                'name'        => 'Kategori Default',
                'description' => null,
            ]
        );

        $response
            ->assertCreated()
            ->assertJsonPath(
                'data.status',
                'active'
            )
            ->assertJsonPath(
                'data.is_active',
                true
            );

        $this->assertDatabaseHas(
            'service_categories',
            [
                'code'   => 'SVC-DEFAULT',
                'status' => 'active',
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STORE VALIDATION - REQUIRED
    |--------------------------------------------------------------------------
    */

    public function test_create_service_category_requires_required_fields(): void
    {
        $response = $this->postJson(
            '/api/v1/service-categories',
            []
        );

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'code',
                'name',
            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | STORE VALIDATION - UNIQUE CODE
    |--------------------------------------------------------------------------
    */

    public function test_service_category_code_must_be_unique(): void
    {
        $this->createServiceCategory([
            'code' => 'SVC-001',
        ]);

        $response = $this->postJson(
            '/api/v1/service-categories',
            [
                'code'        => 'SVC-001',
                'name'        => 'Kategori Duplikat',
                'description' => null,
                'status'      => 'active',
            ]
        );

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'code',
            ]);

        $this->assertDatabaseCount(
            'service_categories',
            1
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STORE VALIDATION - MAX LENGTH CODE
    |--------------------------------------------------------------------------
    */

    public function test_service_category_code_cannot_exceed_30_characters(): void
    {
        $response = $this->postJson(
            '/api/v1/service-categories',
            [
                'code'   => str_repeat('A', 31),
                'name'   => 'Kategori Test',
                'status' => 'active',
            ]
        );

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'code',
            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | STORE VALIDATION - MAX LENGTH NAME
    |--------------------------------------------------------------------------
    */

    public function test_service_category_name_cannot_exceed_150_characters(): void
    {
        $response = $this->postJson(
            '/api/v1/service-categories',
            [
                'code'   => 'SVC-001',
                'name'   => str_repeat('A', 151),
                'status' => 'active',
            ]
        );

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'name',
            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | STORE VALIDATION - STATUS
    |--------------------------------------------------------------------------
    */

    public function test_service_category_status_must_be_valid(): void
    {
        $response = $this->postJson(
            '/api/v1/service-categories',
            [
                'code'   => 'SVC-001',
                'name'   => 'Kategori Test',
                'status' => 'invalid-status',
            ]
        );

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'status',
            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function test_can_show_service_category_detail(): void
    {
        $serviceCategory = $this->createServiceCategory([
            'code'        => 'SVC-001',
            'name'        => 'Layanan Konsultasi',
            'description' => 'Deskripsi layanan.',
            'status'      => 'active',
        ]);

        $response = $this->getJson(
            "/api/v1/service-categories/{$serviceCategory->id}"
        );

        $response
            ->assertOk()
            ->assertJsonPath(
                'success',
                true
            )
            ->assertJsonPath(
                'data.id',
                $serviceCategory->id
            )
            ->assertJsonPath(
                'data.code',
                'SVC-001'
            )
            ->assertJsonPath(
                'data.name',
                'Layanan Konsultasi'
            )
            ->assertJsonPath(
                'data.description',
                'Deskripsi layanan.'
            )
            ->assertJsonPath(
                'data.status',
                'active'
            )
            ->assertJsonPath(
                'data.status_label',
                'Aktif'
            )
            ->assertJsonPath(
                'data.is_active',
                true
            );
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW - NOT FOUND
    |--------------------------------------------------------------------------
    */

    public function test_show_returns_not_found_for_unknown_service_category(): void
    {
        $response = $this->getJson(
            '/api/v1/service-categories/999999'
        );

        $response->assertNotFound();
    }

    /*
    |--------------------------------------------------------------------------
    | PUT UPDATE
    |--------------------------------------------------------------------------
    */

    public function test_can_fully_update_service_category(): void
    {
        $serviceCategory = $this->createServiceCategory([
            'code'        => 'SVC-OLD',
            'name'        => 'Kategori Lama',
            'description' => 'Deskripsi lama.',
            'status'      => 'active',
        ]);

        $payload = [
            'code'        => 'svc-new',
            'name'        => 'Kategori Baru',
            'description' => 'Deskripsi baru.',
            'status'      => 'inactive',
        ];

        $response = $this->putJson(
            "/api/v1/service-categories/{$serviceCategory->id}",
            $payload
        );

        $response
            ->assertOk()
            ->assertJsonPath(
                'success',
                true
            )
            ->assertJsonPath(
                'data.code',
                'SVC-NEW'
            )
            ->assertJsonPath(
                'data.name',
                'Kategori Baru'
            )
            ->assertJsonPath(
                'data.description',
                'Deskripsi baru.'
            )
            ->assertJsonPath(
                'data.status',
                'inactive'
            )
            ->assertJsonPath(
                'data.status_label',
                'Tidak Aktif'
            )
            ->assertJsonPath(
                'data.is_active',
                false
            );

        $this->assertDatabaseHas(
            'service_categories',
            [
                'id'          => $serviceCategory->id,
                'code'        => 'SVC-NEW',
                'name'        => 'Kategori Baru',
                'description' => 'Deskripsi baru.',
                'status'      => 'inactive',
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | PATCH UPDATE
    |--------------------------------------------------------------------------
    */

    public function test_can_partially_update_service_category(): void
    {
        $serviceCategory = $this->createServiceCategory([
            'code'        => 'SVC-001',
            'name'        => 'Kategori Lama',
            'description' => 'Deskripsi awal.',
            'status'      => 'active',
        ]);

        $response = $this->patchJson(
            "/api/v1/service-categories/{$serviceCategory->id}",
            [
                'name' => 'Kategori Hasil Update',
            ]
        );

        $response
            ->assertOk()
            ->assertJsonPath(
                'success',
                true
            )
            ->assertJsonPath(
                'data.code',
                'SVC-001'
            )
            ->assertJsonPath(
                'data.name',
                'Kategori Hasil Update'
            )
            ->assertJsonPath(
                'data.status',
                'active'
            );

        $this->assertDatabaseHas(
            'service_categories',
            [
                'id'     => $serviceCategory->id,
                'code'   => 'SVC-001',
                'name'   => 'Kategori Hasil Update',
                'status' => 'active',
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE UNIQUE CODE
    |--------------------------------------------------------------------------
    */

    public function test_update_can_keep_its_own_code(): void
    {
        $serviceCategory = $this->createServiceCategory([
            'code' => 'SVC-001',
        ]);

        $response = $this->putJson(
            "/api/v1/service-categories/{$serviceCategory->id}",
            [
                'code'        => 'SVC-001',
                'name'        => 'Kategori Update',
                'description' => null,
                'status'      => 'active',
            ]
        );

        $response
            ->assertOk()
            ->assertJsonPath(
                'data.code',
                'SVC-001'
            );
    }

    public function test_update_cannot_use_another_service_category_code(): void
    {
        $first = $this->createServiceCategory([
            'code' => 'SVC-001',
        ]);

        $second = $this->createServiceCategory([
            'code' => 'SVC-002',
        ]);

        $response = $this->putJson(
            "/api/v1/service-categories/{$second->id}",
            [
                'code'        => $first->code,
                'name'        => 'Kategori Dua',
                'description' => null,
                'status'      => 'active',
            ]
        );

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'code',
            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | TOGGLE STATUS
    |--------------------------------------------------------------------------
    */

    public function test_can_toggle_service_category_from_active_to_inactive(): void
    {
        $serviceCategory = $this->createServiceCategory([
            'status' => 'active',
        ]);

        $response = $this->patchJson(
            "/api/v1/service-categories/{$serviceCategory->id}/toggle-status"
        );

        $response
            ->assertOk()
            ->assertJsonPath(
                'success',
                true
            )
            ->assertJsonPath(
                'data.status',
                'inactive'
            )
            ->assertJsonPath(
                'data.status_label',
                'Tidak Aktif'
            )
            ->assertJsonPath(
                'data.is_active',
                false
            );

        $this->assertDatabaseHas(
            'service_categories',
            [
                'id'     => $serviceCategory->id,
                'status' => 'inactive',
            ]
        );
    }

    public function test_can_toggle_service_category_from_inactive_to_active(): void
    {
        $serviceCategory = $this->createServiceCategory([
            'status' => 'inactive',
        ]);

        $response = $this->patchJson(
            "/api/v1/service-categories/{$serviceCategory->id}/toggle-status"
        );

        $response
            ->assertOk()
            ->assertJsonPath(
                'data.status',
                'active'
            )
            ->assertJsonPath(
                'data.status_label',
                'Aktif'
            )
            ->assertJsonPath(
                'data.is_active',
                true
            );

        $this->assertDatabaseHas(
            'service_categories',
            [
                'id'     => $serviceCategory->id,
                'status' => 'active',
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SOFT DELETE
    |--------------------------------------------------------------------------
    */

    public function test_can_soft_delete_service_category(): void
    {
        $serviceCategory = $this->createServiceCategory([
            'code' => 'SVC-DELETE',
        ]);

        $response = $this->deleteJson(
            "/api/v1/service-categories/{$serviceCategory->id}"
        );

        $response
            ->assertOk()
            ->assertJsonPath(
                'success',
                true
            )
            ->assertJsonPath(
                'message',
                'Kategori layanan berhasil dipindahkan ke sampah.'
            );

        $this->assertSoftDeleted(
            'service_categories',
            [
                'id' => $serviceCategory->id,
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SOFT DELETED DATA NOT SHOW ON NORMAL INDEX
    |--------------------------------------------------------------------------
    */

    public function test_soft_deleted_service_category_is_not_returned_in_normal_index(): void
    {
        $activeCategory = $this->createServiceCategory([
            'code' => 'SVC-ACTIVE',
        ]);

        $deletedCategory = $this->createServiceCategory([
            'code' => 'SVC-DELETED',
        ]);

        $deletedCategory->delete();

        $response = $this->getJson(
            '/api/v1/service-categories'
        );

        $response
            ->assertOk()
            ->assertJsonPath(
                'meta.total',
                1
            )
            ->assertJsonPath(
                'data.0.id',
                $activeCategory->id
            );

        $response->assertJsonMissing([
            'code' => 'SVC-DELETED',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | TRASHED
    |--------------------------------------------------------------------------
    */

    public function test_can_get_trashed_service_categories(): void
    {
        $normalCategory = $this->createServiceCategory([
            'code' => 'SVC-NORMAL',
        ]);

        $deletedCategory = $this->createServiceCategory([
            'code' => 'SVC-TRASH',
            'name' => 'Kategori Sampah',
        ]);

        $deletedCategory->delete();

        $response = $this->getJson(
            '/api/v1/service-categories/trashed'
        );

        $response
            ->assertOk()
            ->assertJsonPath(
                'success',
                true
            )
            ->assertJsonPath(
                'meta.total',
                1
            )
            ->assertJsonPath(
                'data.0.id',
                $deletedCategory->id
            )
            ->assertJsonPath(
                'data.0.code',
                'SVC-TRASH'
            );

        $response->assertJsonMissing([
            'id'   => $normalCategory->id,
            'code' => 'SVC-NORMAL',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | RESTORE
    |--------------------------------------------------------------------------
    */

    public function test_can_restore_soft_deleted_service_category(): void
    {
        $serviceCategory = $this->createServiceCategory([
            'code' => 'SVC-RESTORE',
            'name' => 'Kategori Restore',
        ]);

        $id = $serviceCategory->id;

        $serviceCategory->delete();

        $this->assertSoftDeleted(
            'service_categories',
            [
                'id' => $id,
            ]
        );

        $response = $this->patchJson(
            "/api/v1/service-categories/{$id}/restore"
        );

        $response
            ->assertOk()
            ->assertJsonPath(
                'success',
                true
            )
            ->assertJsonPath(
                'message',
                'Kategori layanan berhasil dipulihkan.'
            )
            ->assertJsonPath(
                'data.id',
                $id
            )
            ->assertJsonPath(
                'data.code',
                'SVC-RESTORE'
            )
            ->assertJsonPath(
                'data.deleted_at',
                null
            );

        $this->assertDatabaseHas(
            'service_categories',
            [
                'id'         => $id,
                'code'       => 'SVC-RESTORE',
                'deleted_at' => null,
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RESTORE - NOT FOUND
    |--------------------------------------------------------------------------
    */

    public function test_restore_returns_not_found_when_trashed_data_does_not_exist(): void
    {
        $response = $this->patchJson(
            '/api/v1/service-categories/999999/restore'
        );

        $response
            ->assertNotFound()
            ->assertJsonPath(
                'success',
                false
            );
    }

    /*
    |--------------------------------------------------------------------------
    | FORCE DELETE
    |--------------------------------------------------------------------------
    */

    public function test_can_force_delete_service_category(): void
    {
        $serviceCategory = $this->createServiceCategory([
            'code' => 'SVC-FORCE',
            'name' => 'Kategori Force Delete',
        ]);

        $id = $serviceCategory->id;

        $serviceCategory->delete();

        $this->assertSoftDeleted(
            'service_categories',
            [
                'id' => $id,
            ]
        );

        $response = $this->deleteJson(
            "/api/v1/service-categories/{$id}/force-delete"
        );

        $response
            ->assertOk()
            ->assertJsonPath(
                'success',
                true
            )
            ->assertJsonPath(
                'message',
                'Kategori layanan berhasil dihapus permanen.'
            );

        $this->assertDatabaseMissing(
            'service_categories',
            [
                'id' => $id,
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | FORCE DELETE - NOT FOUND
    |--------------------------------------------------------------------------
    */

    public function test_force_delete_returns_not_found_when_trashed_data_does_not_exist(): void
    {
        $response = $this->deleteJson(
            '/api/v1/service-categories/999999/force-delete'
        );

        $response
            ->assertNotFound()
            ->assertJsonPath(
                'success',
                false
            );
    }

    /*
    |--------------------------------------------------------------------------
    | SORT
    |--------------------------------------------------------------------------
    */

    public function test_can_sort_service_categories_by_name(): void
    {
        $this->createServiceCategory([
            'code' => 'SVC-B',
            'name' => 'Bravo',
        ]);

        $this->createServiceCategory([
            'code' => 'SVC-A',
            'name' => 'Alpha',
        ]);

        $response = $this->getJson(
            '/api/v1/service-categories?sort_by=name&sort_direction=asc'
        );

        $response
            ->assertOk()
            ->assertJsonPath(
                'data.0.name',
                'Alpha'
            )
            ->assertJsonPath(
                'data.1.name',
                'Bravo'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | PAGINATION
    |--------------------------------------------------------------------------
    */

    public function test_service_category_index_supports_pagination(): void
    {
        for ($i = 1; $i <= 20; $i++) {
            $this->createServiceCategory();
        }

        $response = $this->getJson(
            '/api/v1/service-categories?per_page=10'
        );

        $response
            ->assertOk()
            ->assertJsonPath(
                'meta.current_page',
                1
            )
            ->assertJsonPath(
                'meta.per_page',
                10
            )
            ->assertJsonPath(
                'meta.total',
                20
            );

        $this->assertCount(
            10,
            $response->json('data')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | INVALID INDEX PARAMETER
    |--------------------------------------------------------------------------
    */

    public function test_index_rejects_invalid_status_filter(): void
    {
        $response = $this->getJson(
            '/api/v1/service-categories?status=invalid'
        );

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'status',
            ]);
    }

    public function test_index_rejects_invalid_sort_column(): void
    {
        $response = $this->getJson(
            '/api/v1/service-categories?sort_by=password'
        );

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'sort_by',
            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER
    |--------------------------------------------------------------------------
    |
    | Helper ini sengaja tidak menggunakan factory sehingga test tetap bisa
    | dijalankan meskipun ServiceCategoryFactory belum dibuat.
    |
    */

    private function createServiceCategory(
        array $attributes = []
    ): ServiceCategory {
        $sequence = self::$sequence++;

        return ServiceCategory::query()->create(
            array_merge(
                [
                    'code'        => sprintf(
                        'SVC-TEST-%03d',
                        $sequence
                    ),

                    'name'        => sprintf(
                        'Kategori Layanan Test %03d',
                        $sequence
                    ),

                    'description' => sprintf(
                        'Deskripsi kategori layanan test %03d.',
                        $sequence
                    ),

                    'status'      => 'active',
                ],

                $attributes
            )
        );
    }
}

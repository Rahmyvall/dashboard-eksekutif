<?php

declare (strict_types = 1);

namespace Database\Factories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Department>
 */
class DepartmentFactory extends Factory
{
    /**
     * Model yang digunakan factory.
     *
     * @var class-string<Department>
     */
    protected $model = Department::class;

    /**
     * Data default department untuk testing dan seeding.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code'        => strtoupper(
                fake()->unique()->bothify('DEPT-####-??')
            ),
            'name'        => fake()->unique()->company() . ' Department',
            'description' => fake()->optional(0.85)->sentence(12),
            'status'      => fake()->randomElement([
                'active',
                'inactive',
            ]),
        ];
    }

    /**
     * Department aktif.
     */
    public function active(): static
    {
        return $this->state(fn(array $attributes): array=> [
            'status' => 'active',
        ]);
    }

    /**
     * Department tidak aktif.
     */
    public function inactive(): static
    {
        return $this->state(fn(array $attributes): array=> [
            'status' => 'inactive',
        ]);
    }

    /**
     * Department tanpa deskripsi.
     */
    public function withoutDescription(): static
    {
        return $this->state(fn(array $attributes): array=> [
            'description' => null,
        ]);
    }

    /**
     * Department yang telah terkena soft delete.
     */
    public function trashed(): static
    {
        return $this->state(fn(array $attributes): array=> [
            'deleted_at' => now()->subDays(
                fake()->numberBetween(1, 30)
            ),
        ]);
    }
}

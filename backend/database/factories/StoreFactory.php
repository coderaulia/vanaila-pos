<?php

namespace Database\Factories;

use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Store>
 */
class StoreFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Vanaila '.fake()->city(),
            'code' => strtoupper(fake()->unique()->bothify('VNT-???')),
            'timezone' => 'Asia/Jakarta',
            'currency' => 'IDR',
            'address' => fake()->streetAddress().', '.fake()->city(),
            'phone' => fake()->numerify('+62 812 #### ####'),
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}

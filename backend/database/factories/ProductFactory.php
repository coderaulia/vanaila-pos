<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = Str::title(fake()->unique()->words(3, true));
        $skuSeed = strtoupper(Str::slug($name, ''));
        $sku = substr($skuSeed, 0, 8).'-'.fake()->unique()->numerify('##');

        return [
            'store_id' => Store::factory(),
            'sku' => $sku,
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->unique()->numerify('##'),
            'category' => fake()->randomElement(['Coffee', 'Tea', 'Signature', 'Blended', 'Pastry']),
            'description' => fake()->sentence(),
            'price_cents' => fake()->numberBetween(25000, 85000),
            'stock_quantity' => fake()->numberBetween(0, 120),
            'is_active' => true,
        ];
    }

    public function lowStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock_quantity' => fake()->numberBetween(0, 10),
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}

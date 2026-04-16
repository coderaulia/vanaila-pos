<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quantity = fake()->numberBetween(1, 4);
        $unitPrice = fake()->numberBetween(20000, 90000);

        return [
            'order_id' => Order::factory(),
            'product_id' => Product::factory(),
            'name_snapshot' => fake()->words(3, true),
            'sku_snapshot' => strtoupper(fake()->bothify('SKU-###??')),
            'quantity' => $quantity,
            'unit_price_cents' => $unitPrice,
            'line_total_cents' => $quantity * $unitPrice,
        ];
    }
}

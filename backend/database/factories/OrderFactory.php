<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal = fake()->numberBetween(50000, 350000);
        $tax = (int) round($subtotal * 0.1);
        $discount = fake()->boolean(20) ? fake()->numberBetween(0, (int) round($subtotal * 0.15)) : 0;
        $status = fake()->randomElement(['paid', 'open']);
        $placedAt = now()->subMinutes(fake()->numberBetween(5, 720));

        return [
            'order_number' => 'ORD-'.fake()->unique()->numerify('########'),
            'store_id' => Store::factory(),
            'cashier_id' => User::factory()->cashier(),
            'status' => $status,
            'payment_method' => fake()->randomElement(['cash', 'card', 'qris']),
            'subtotal_cents' => $subtotal,
            'tax_cents' => $tax,
            'discount_cents' => $discount,
            'total_cents' => max($subtotal + $tax - $discount, 0),
            'notes' => fake()->boolean(25) ? fake()->sentence() : null,
            'placed_at' => $placedAt,
            'paid_at' => $status === 'paid' ? $placedAt : null,
        ];
    }

    public function paid(): static
    {
        return $this->state(function (array $attributes) {
            $placedAt = now()->subMinutes(fake()->numberBetween(5, 720));

            return [
                'status' => 'paid',
                'paid_at' => $placedAt,
                'placed_at' => $placedAt,
            ];
        });
    }

    public function open(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'open',
                'paid_at' => null,
            ];
        });
    }
}

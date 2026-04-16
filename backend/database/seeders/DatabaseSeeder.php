<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $tebet = Store::factory()->create([
            'name' => 'Vanaila Tebet',
            'code' => 'VNT-TBT',
            'timezone' => 'Asia/Jakarta',
            'currency' => 'IDR',
            'phone' => '+62 812 0000 0001',
            'address' => 'Jl. Tebet Barat Dalam No. 8, Jakarta Selatan',
            'is_active' => true,
        ]);

        $bsd = Store::factory()->create([
            'name' => 'Vanaila BSD',
            'code' => 'VNT-BSD',
            'timezone' => 'Asia/Jakarta',
            'currency' => 'IDR',
            'phone' => '+62 812 0000 0002',
            'address' => 'Jl. BSD Raya Utama, Tangerang Selatan',
            'is_active' => true,
        ]);

        $extraStores = Store::factory()
            ->count(2)
            ->create();

        User::factory()->superadmin()->create([
            'name' => 'Nadya Superadmin',
            'email' => 'superadmin@vanaila.test',
            'phone' => '+62 812 1111 1000',
            'is_active' => true,
        ]);

        User::factory()->admin()->create([
            'name' => 'Raka Admin',
            'email' => 'admin@vanaila.test',
            'phone' => '+62 812 1111 2000',
            'is_active' => true,
        ]);

        $cashier = User::factory()->cashier()->create([
            'name' => 'Maya Cashier',
            'email' => 'cashier@vanaila.test',
            'phone' => '+62 812 1111 3000',
            'is_active' => true,
        ]);

        User::factory()->admin()->count(2)->create();
        User::factory()->cashier()->count(6)->create();

        $latte = Product::factory()->for($tebet)->create([
            'sku' => 'LATTE-12',
            'name' => 'Vanilla Cloud Latte',
            'slug' => 'vanilla-cloud-latte',
            'category' => 'Coffee',
            'description' => 'Signature espresso with vanilla cream cap.',
            'price_cents' => 55000,
            'stock_quantity' => 72,
            'is_active' => true,
        ]);

        $frappe = Product::factory()->for($tebet)->create([
            'sku' => 'FRAP-09',
            'name' => 'Caramel Bean Frappe',
            'slug' => 'caramel-bean-frappe',
            'category' => 'Blended',
            'description' => 'Cold caramel blend for afternoon service.',
            'price_cents' => 62500,
            'stock_quantity' => 31,
            'is_active' => true,
        ]);

        Product::factory()->for($bsd)->create([
            'sku' => 'MATCH-08',
            'name' => 'Matcha Oat Shake',
            'slug' => 'matcha-oat-shake',
            'category' => 'Signature',
            'description' => 'Oat milk shake with ceremonial matcha.',
            'price_cents' => 59500,
            'stock_quantity' => 18,
            'is_active' => true,
        ]);

        Product::factory()->count(6)->for($tebet)->create();
        Product::factory()->count(6)->for($bsd)->create();
        $extraStores->each(fn (Store $store) => Product::factory()->count(5)->for($store)->create());

        $order = Order::factory()
            ->paid()
            ->for($tebet, 'store')
            ->for($cashier, 'cashier')
            ->create([
                'order_number' => 'ORD-20260413-0001',
                'payment_method' => 'cash',
                'notes' => 'Seeded example order.',
                'placed_at' => now()->subHours(2),
                'paid_at' => now()->subHours(2),
            ]);

        $order->items()->createMany([
            [
                'product_id' => $latte->id,
                'name_snapshot' => $latte->name,
                'sku_snapshot' => $latte->sku,
                'quantity' => 2,
                'unit_price_cents' => $latte->price_cents,
                'line_total_cents' => 110000,
            ],
            [
                'product_id' => $frappe->id,
                'name_snapshot' => $frappe->name,
                'sku_snapshot' => $frappe->sku,
                'quantity' => 1,
                'unit_price_cents' => $frappe->price_cents,
                'line_total_cents' => 62500,
            ],
        ]);

        $order->update([
            'subtotal_cents' => 172500,
            'tax_cents' => 17250,
            'discount_cents' => 0,
            'total_cents' => 189750,
        ]);

        $allStores = Store::query()->get();
        $cashiers = User::query()->where('role', UserRole::Cashier->value)->get();

        $allStores->each(function (Store $store) use ($cashiers) {
            $products = Product::query()
                ->where('store_id', $store->id)
                ->where('is_active', true)
                ->get();

            $this->seedOrdersForStore($store, $cashiers, $products, 8);
        });

    }

    private function seedOrdersForStore(
        Store $store,
        Collection $cashiers,
        Collection $products,
        int $count
    ): void {
        if ($products->isEmpty() || $cashiers->isEmpty()) {
            return;
        }

        Order::factory()
            ->count($count)
            ->for($store, 'store')
            ->state(fn (array $attributes) => [
                'cashier_id' => $cashiers->random()->id,
            ])
            ->create()
            ->each(function (Order $order) use ($products) {
                $itemCount = min($products->count(), fake()->numberBetween(1, 3));
                $selectedProducts = $products->shuffle()->take($itemCount);

                $subtotal = 0;

                foreach ($selectedProducts as $product) {
                    $quantity = fake()->numberBetween(1, 3);
                    $lineTotal = $product->price_cents * $quantity;
                    $subtotal += $lineTotal;

                    $order->items()->create([
                        'product_id' => $product->id,
                        'name_snapshot' => $product->name,
                        'sku_snapshot' => $product->sku,
                        'quantity' => $quantity,
                        'unit_price_cents' => $product->price_cents,
                        'line_total_cents' => $lineTotal,
                    ]);
                }

                $tax = (int) round($subtotal * 0.1);
                $discount = $order->status === 'paid'
                    ? fake()->numberBetween(0, (int) round($subtotal * 0.1))
                    : 0;

                $order->update([
                    'subtotal_cents' => $subtotal,
                    'tax_cents' => $tax,
                    'discount_cents' => $discount,
                    'total_cents' => max($subtotal + $tax - $discount, 0),
                    'paid_at' => $order->status === 'paid' ? ($order->paid_at ?? now()) : null,
                ]);
            });
    }
}

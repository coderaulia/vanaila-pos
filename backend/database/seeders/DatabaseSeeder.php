<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $tebet = Store::query()->updateOrCreate(
            ['code' => 'VNT-TBT'],
            [
                'name' => 'Vanaila Tebet',
                'timezone' => 'Asia/Jakarta',
                'currency' => 'IDR',
                'phone' => '+62 812 0000 0001',
                'address' => 'Jl. Tebet Barat Dalam No. 8, Jakarta Selatan',
                'is_active' => true,
            ],
        );

        $bsd = Store::query()->updateOrCreate(
            ['code' => 'VNT-BSD'],
            [
                'name' => 'Vanaila BSD',
                'timezone' => 'Asia/Jakarta',
                'currency' => 'IDR',
                'phone' => '+62 812 0000 0002',
                'address' => 'Jl. BSD Raya Utama, Tangerang Selatan',
                'is_active' => true,
            ],
        );

        $superadmin = User::query()->updateOrCreate(
            ['email' => 'superadmin@vanaila.test'],
            [
                'name' => 'Nadya Superadmin',
                'phone' => '+62 812 1111 1000',
                'role' => UserRole::Superadmin,
                'is_active' => true,
                'password' => Hash::make('password'),
            ],
        );

        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@vanaila.test'],
            [
                'name' => 'Raka Admin',
                'phone' => '+62 812 1111 2000',
                'role' => UserRole::Admin,
                'is_active' => true,
                'password' => Hash::make('password'),
            ],
        );

        $cashier = User::query()->updateOrCreate(
            ['email' => 'cashier@vanaila.test'],
            [
                'name' => 'Maya Cashier',
                'phone' => '+62 812 1111 3000',
                'role' => UserRole::Cashier,
                'is_active' => true,
                'password' => Hash::make('password'),
            ],
        );

        $products = [
            [
                'store_id' => $tebet->id,
                'sku' => 'LATTE-12',
                'name' => 'Vanilla Cloud Latte',
                'slug' => 'vanilla-cloud-latte',
                'category' => 'Coffee',
                'description' => 'Signature espresso with vanilla cream cap.',
                'price_cents' => 55000,
                'stock_quantity' => 72,
                'is_active' => true,
            ],
            [
                'store_id' => $tebet->id,
                'sku' => 'FRAP-09',
                'name' => 'Caramel Bean Frappe',
                'slug' => 'caramel-bean-frappe',
                'category' => 'Blended',
                'description' => 'Cold caramel blend for afternoon service.',
                'price_cents' => 62500,
                'stock_quantity' => 31,
                'is_active' => true,
            ],
            [
                'store_id' => $bsd->id,
                'sku' => 'MATCH-08',
                'name' => 'Matcha Oat Shake',
                'slug' => 'matcha-oat-shake',
                'category' => 'Signature',
                'description' => 'Oat milk shake with ceremonial matcha.',
                'price_cents' => 59500,
                'stock_quantity' => 18,
                'is_active' => true,
            ],
        ];

        foreach ($products as $attributes) {
            Product::query()->updateOrCreate(['sku' => $attributes['sku']], $attributes);
        }

        $latte = Product::query()->where('sku', 'LATTE-12')->firstOrFail();
        $frappe = Product::query()->where('sku', 'FRAP-09')->firstOrFail();

        $order = Order::query()->updateOrCreate(
            ['order_number' => 'ORD-20260413-0001'],
            [
                'store_id' => $tebet->id,
                'cashier_id' => $cashier->id,
                'status' => 'paid',
                'payment_method' => 'cash',
                'subtotal_cents' => 172500,
                'tax_cents' => 17250,
                'discount_cents' => 0,
                'total_cents' => 189750,
                'notes' => 'Seeded example order.',
                'placed_at' => now()->subHours(2),
                'paid_at' => now()->subHours(2),
            ],
        );

        $order->items()->delete();
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
    }
}

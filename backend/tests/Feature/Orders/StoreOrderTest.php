<?php

namespace Tests\Feature\Orders;

use App\Enums\UserRole;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class StoreOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_authenticated_cashier_can_create_an_order(): void
    {
        $store = Store::query()->create([
            'name' => 'Vanaila Tebet',
            'code' => 'VNT-TBT',
            'timezone' => 'Asia/Jakarta',
            'currency' => 'IDR',
            'is_active' => true,
        ]);

        $product = Product::query()->create([
            'store_id' => $store->id,
            'sku' => 'LATTE-12',
            'name' => 'Vanilla Cloud Latte',
            'slug' => 'vanilla-cloud-latte',
            'category' => 'Coffee',
            'price_cents' => 55000,
            'stock_quantity' => 15,
            'is_active' => true,
        ]);

        $cashier = User::factory()->create([
            'role' => UserRole::Cashier->value,
        ]);

        Sanctum::actingAs($cashier);

        $response = $this->postJson('/api/v1/orders', [
            'store_id' => $store->id,
            'payment_method' => 'cash',
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                ],
            ],
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('data.subtotal_cents', 110000)
            ->assertJsonPath('data.total_cents', 110000);
    }
}

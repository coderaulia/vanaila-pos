<?php

namespace Tests\Feature\Orders;

use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_cashier_can_update_their_own_order_status()
    {
        $store = Store::query()->create([
            'name' => 'Store 1',
            'code' => 'ST-001',
        ]);
        $cashier = User::factory()->create(['role' => 'cashier']);
        $product = Product::query()->create([
            'store_id' => $store->id,
            'sku' => 'SKU-001',
            'name' => 'Product 1',
            'slug' => 'product-1',
            'price_cents' => 100,
            'stock_quantity' => 10,
        ]);

        $order = Order::query()->create([
            'order_number' => 'ORD-001',
            'store_id' => $store->id,
            'cashier_id' => $cashier->id,
            'status' => 'open',
            'payment_method' => 'cash',
            'subtotal_cents' => 200,
            'total_cents' => 200,
        ]);
        $order->items()->create([
            'product_id' => $product->id,
            'quantity' => 2,
            'unit_price_cents' => 100,
            'line_total_cents' => 200,
            'name_snapshot' => $product->name,
            'sku_snapshot' => $product->sku,
        ]);

        $response = $this->actingAs($cashier)->putJson("/api/v1/orders/{$order->id}", [
            'status' => 'paid',
            'payment_method' => 'cash',
        ]);

        $response->assertOk();
        $this->assertEquals('paid', $order->fresh()->status);
        $this->assertNotNull($order->fresh()->paid_at);
    }

    public function test_cancelling_order_restores_stock()
    {
        $store = Store::query()->create([
            'name' => 'Store 1',
            'code' => 'ST-001',
        ]);
        $cashier = User::factory()->create(['role' => 'cashier']);
        $product = Product::query()->create([
            'store_id' => $store->id,
            'sku' => 'SKU-001',
            'name' => 'Product 1',
            'slug' => 'product-1',
            'price_cents' => 100,
            'stock_quantity' => 8, // Pre-deducted
        ]);

        $order = Order::query()->create([
            'order_number' => 'ORD-001',
            'store_id' => $store->id,
            'cashier_id' => $cashier->id,
            'status' => 'open',
            'payment_method' => 'cash',
            'subtotal_cents' => 200,
            'total_cents' => 200,
        ]);
        $order->items()->create([
            'product_id' => $product->id,
            'quantity' => 2,
            'unit_price_cents' => 100,
            'line_total_cents' => 200,
            'name_snapshot' => $product->name,
            'sku_snapshot' => $product->sku,
        ]);

        $response = $this->actingAs($cashier)->putJson("/api/v1/orders/{$order->id}", [
            'status' => 'cancelled',
        ]);

        $response->assertOk();
        $this->assertEquals('cancelled', $order->fresh()->status);
        $this->assertEquals(10, $product->fresh()->stock_quantity); // Stock restored
    }
}

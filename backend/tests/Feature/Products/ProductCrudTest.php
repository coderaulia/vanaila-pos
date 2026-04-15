<?php

namespace Tests\Feature\Products;

use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_product()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $store = Store::query()->create(['name' => 'Store 1', 'code' => 'ST-01']);

        $response = $this->actingAs($admin)->postJson('/api/v1/admin/products', [
            'store_id' => $store->id,
            'sku' => 'TEST-01',
            'name' => 'Test Product',
            'slug' => 'test-product',
            'price_cents' => 500,
            'stock_quantity' => 10,
        ]);

        $response->assertCreated()->assertJsonPath('data.sku', 'TEST-01');
    }

    public function test_admin_can_soft_delete_product()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $store = Store::query()->create(['name' => 'Store 1', 'code' => 'ST-01']);
        $product = Product::query()->create([
            'store_id' => $store->id,
            'sku' => 'TEST-01',
            'name' => 'Test',
            'slug' => 'test',
            'price_cents' => 500,
            'stock_quantity' => 10,
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->deleteJson("/api/v1/admin/products/{$product->id}");

        $response->assertOk();
        $this->assertFalse($product->fresh()->is_active);
    }
}

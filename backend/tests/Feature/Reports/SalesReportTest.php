<?php

namespace Tests\Feature\Reports;

use App\Models\Order;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SalesReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_sales_report()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $store = Store::query()->create(['name' => 'Store 1', 'code' => 'ST-01']);

        Order::query()->create([
            'order_number' => 'ORD-001',
            'store_id' => $store->id,
            'cashier_id' => $admin->id,
            'status' => 'paid',
            'payment_method' => 'cash',
            'subtotal_cents' => 1000,
            'total_cents' => 1000,
            'placed_at' => now(),
        ]);

        $response = $this->actingAs($admin)->getJson('/api/v1/admin/reports/sales');

        $response->assertOk()
            ->assertJsonPath('summary.total_revenue_cents', 1000)
            ->assertJsonPath('summary.total_orders', 1);
    }

    public function test_superadmin_can_view_store_performance_report()
    {
        $superadmin = User::factory()->create(['role' => 'superadmin']);
        $store = Store::query()->create(['name' => 'Store 2', 'code' => 'ST-02']);

        Order::query()->create([
            'order_number' => 'ORD-002',
            'store_id' => $store->id,
            'cashier_id' => $superadmin->id,
            'status' => 'paid',
            'payment_method' => 'cash',
            'subtotal_cents' => 3000,
            'total_cents' => 3000,
            'placed_at' => now(),
        ]);

        $response = $this->actingAs($superadmin)->getJson('/api/v1/superadmin/reports/stores');

        $response->assertOk()
            ->assertJsonPath('data.0.total_revenue_cents', 3000);
    }
}

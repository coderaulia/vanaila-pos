<?php

namespace Tests\Feature\Stores;

use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_superadmin_can_soft_delete_store()
    {
        $superadmin = User::factory()->create(['role' => 'superadmin']);
        $store = Store::query()->create([
            'name' => 'Store 1',
            'code' => 'ST-01',
            'is_active' => true,
        ]);

        $response = $this->actingAs($superadmin)->deleteJson("/api/v1/superadmin/stores/{$store->id}");

        $response->assertOk();
        $this->assertFalse($store->fresh()->is_active);
    }
}

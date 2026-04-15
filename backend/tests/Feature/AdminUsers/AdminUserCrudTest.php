<?php

namespace Tests\Feature\AdminUsers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_superadmin_can_soft_delete_admin()
    {
        $superadmin = User::factory()->create(['role' => 'superadmin']);
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        $response = $this->actingAs($superadmin)->deleteJson("/api/v1/superadmin/admins/{$admin->id}");

        $response->assertOk();
        $this->assertFalse($admin->fresh()->is_active);
    }

    public function test_superadmin_cannot_soft_delete_another_superadmin()
    {
        $superadmin1 = User::factory()->create(['role' => 'superadmin', 'is_active' => true]);
        $superadmin2 = User::factory()->create(['role' => 'superadmin', 'is_active' => true]);

        $response = $this->actingAs($superadmin1)->deleteJson("/api/v1/superadmin/admins/{$superadmin2->id}");

        $response->assertForbidden();
        $this->assertTrue($superadmin2->fresh()->is_active);
    }
}

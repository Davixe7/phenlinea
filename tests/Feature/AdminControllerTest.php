<?php

namespace Tests\Feature;

use App\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_list_all_admins()
    {
        Admin::factory()->count(3)->create();

        $response = $this->getJson('/api/v2/admin/admins');

        $response->assertStatus(200)
                 ->assertJsonCount(3, 'data');
    }

    /** @test */
    public function it_can_create_an_admin()
    {
        $adminData = Admin::factory()->make(['nit'=>'123456'])->toArray();

        $response = $this->postJson('/api/v2/admin/admins', $adminData);

        $response->assertStatus(201)
                 ->assertJsonStructure(['data' => ['id', 'name', 'email']]);
    }

    /** @test */
    public function it_can_show_an_admin()
    {
        $admin = Admin::factory()->create();

        $response = $this->getJson("/api/v2/admin/admins/{$admin->id}");

        $response->assertStatus(200)
                 ->assertJsonStructure(['data' => ['id', 'name', 'email']]);
    }

    /** @test */
    public function it_can_update_an_admin()
    {
        $admin = Admin::factory()->create();
        $updateData = ['name' => 'Updated Name'];

        $response = $this->putJson("/api/v2/admin/admins/{$admin->id}", $updateData);

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Updated Name']);
    }

    /** @test */
    public function it_can_delete_an_admin()
    {
        $admin = Admin::factory()->create();

        $response = $this->deleteJson("/api/v2/admin/admins/{$admin->id}");

        $response->assertStatus(200)
                 ->assertJson(['message'=>'Admin deleted successfully']);
    }
}

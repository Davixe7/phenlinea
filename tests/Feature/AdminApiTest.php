<?php

namespace Tests\Feature;

use App\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminApiTest extends TestCase
{
    /** @test */
    public function it_can_create_an_admin()
    {
        $data = [
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'contact_email' => 'contact@example.com',
            'name' => 'Admin Name',
            'nit' => '123456789',
            'phone' => '1234567890',
            'address' => 'Sample Address',
            'status' => 1
        ];

        $response = $this->postJson('/api/v2/admins', $data);

        $response->assertStatus(201)->assertJsonFragment([
            'email' => 'admin@example.com',
            'name' => 'Admin Name',
        ]);
    }

    /** @test */
    public function it_can_fetch_admins_list()
    {
        Admin::factory()->count(5)->create();

        $response = $this->getJson('/api/v2/admins');

        $response->assertStatus(200)->assertJsonStructure([
            'data' => ['*' => ['id', 'email', 'name', 'phone']]
        ]);
    }

    /** @test */
    public function it_can_update_an_admin()
    {
        $admin = Admin::factory()->create();
        $data = array_merge($admin->toArray(), ['name' => 'Updated Name']);

        $response = $this->putJson("/api/v2/admins/{$admin->id}", $data);

        $response->assertStatus(200)->assertJsonFragment([
            'name' => 'Updated Name',
        ]);
    }
}

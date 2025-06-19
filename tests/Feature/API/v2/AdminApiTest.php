<?php

namespace Tests\Feature\API\v2;

use App\Admin;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminApiTest extends TestCase
{
    use RefreshDatabase;

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

        $user = User::factory(1)->createOne();
        $response = $this
        ->withHeader("Authorization",  "Bearer $user->api_token")
        ->postJson('/api/v2/admins', $data);

        $response->assertStatus(201)->assertJsonFragment([
            'email' => 'admin@example.com',
            'name' => 'Admin Name',
        ]);
    }

    /** @test */
    public function it_can_fetch_admins_list()
    {
        Admin::factory()->count(5)->create();

        $user = User::factory(1)->createOne();
        $response = $this
        ->withHeader("Authorization",  "Bearer $user->api_token")
        ->getJson('/api/v2/admins');

        $response->assertStatus(200)->assertJsonStructure([
            'data' => ['*' => ['id', 'email', 'name', 'phone']]
        ]);
    }

    /** @test */
    public function it_can_update_an_admin()
    {
        $admin = Admin::factory()->create();
        $data = array_merge($admin->toArray(), ['name' => 'Updated Name']);
        unset($data['api_token']);

        $user = User::factory(1)->createOne();
        $response = $this
        ->withHeader("Authorization",  "Bearer $user->api_token")
        ->putJson("/api/v2/admins/{$admin->id}", $data);

        $response->assertStatus(200)->assertJsonFragment([
            'name' => 'Updated Name',
        ]);
    }
}

<?php

namespace Tests\Feature\Api\V2;

use App\Admin;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Porteria;
use App\User;

class PorteriaApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_porteria()
    {
        $admin = Admin::factory()->createOne();
        $data = [
            'name' => 'Portería Central',
            'email' => 'porteria@example.com',
            'password' => bcrypt('password'),
            'admin_id' => $admin->id,
        ];

        $user = User::factory(1)->createOne();
        $response = $this
        ->withHeader("Authorization",  "Bearer $user->api_token")
        ->postJson('/api/v2/porterias', $data);

        $response
        ->assertStatus(201)
        ->assertJsonFragment(['email' => 'porteria@example.com']);
    }

    /** @test */
    public function it_can_fetch_porterias_list()
    {
        Admin::factory()->createOne();
        Porteria::factory()->count(5)->create();

        $user = User::factory(1)->createOne();
        $response = $this
        ->withHeader("Authorization",  "Bearer $user->api_token")
        ->getJson('/api/v2/porterias');

        $response
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => ['*' => ['id', 'name', 'email', 'admin_id']]
        ]);
    }

    /** @test */
    public function it_can_update_a_porteria()
    {
        Admin::factory()->createOne();
        $porteria = Porteria::factory()->create();
        $data = ['name' => 'Portería Actualizada'];

        $user = User::factory(1)->createOne();
        $response = $this
        ->withHeader("Authorization",  "Bearer $user->api_token")
        ->putJson("/api/v2/porterias/{$porteria->id}", $data);

        $response
        ->assertStatus(200)
        ->assertJsonFragment(['name' => 'Portería Actualizada']);
    }

    /** @test */
    public function it_can_delete_a_porteria()
    {
        Admin::factory()->createOne();
        $porteria = Porteria::factory()->create();

        $user = User::factory(1)->createOne();
        $response = $this
        ->withHeader("Authorization",  "Bearer $user->api_token")
        ->deleteJson("/api/v2/porterias/{$porteria->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('porterias', ['id' => $porteria->id]);
    }
}

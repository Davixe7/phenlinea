<?php

namespace Tests\Feature\Api\V2;

use App\Admin;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Porteria;

class PorteriaApiTest extends TestCase
{
    /** @test */
    public function it_can_create_a_porteria()
    {
        $data = [
            'name' => 'Portería Central',
            'email' => 'porteria@example.com',
            'password' => bcrypt('password'),
            'admin_id' => Admin::first()->id,
        ];

        $response = $this->postJson('/api/v2/porterias', $data);

        $response->assertStatus(201)
                 ->assertJsonFragment(['email' => 'porteria@example.com']);
    }

    /** @test */
    public function it_can_fetch_porterias_list()
    {
        Porteria::factory()->count(5)->create();

        $response = $this->getJson('/api/v2/porterias');

        $response
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => ['*' => ['id', 'name', 'email', 'admin_id']]
        ]);
    }

    /** @test */
    public function it_can_update_a_porteria()
    {
        $porteria = Porteria::factory()->create();
        $data = ['name' => 'Portería Actualizada'];

        $response = $this->putJson("/api/v2/porterias/{$porteria->id}", $data);

        $response
        ->assertStatus(200)
        ->assertJsonFragment(['name' => 'Portería Actualizada']);
    }

    /** @test */
    public function it_can_delete_a_porteria()
    {
        $porteria = Porteria::factory()->create();

        $response = $this->deleteJson("/api/v2/porterias/{$porteria->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('porterias', ['id' => $porteria->id]);
    }
}

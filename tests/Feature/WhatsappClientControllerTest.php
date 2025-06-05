<?php

namespace Tests\Feature;

use App\WhatsappClient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WhatsappClientControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    /** @test */
    public function it_can_list_all_whatsapp_clients()
    {
        WhatsappClient::factory()->count(3)->create();
        $response = $this->get('/api/v2/admin/whatsapp_clients');

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_update_a_whatsapp_client()
    {
        $whatsapp_client = WhatsappClient::factory()->create();
        $data            = WhatsappClient::factory()->make()->toArray();
        $response = $this->putJson('/api/v2/admin/whatsapp_clients/' . $whatsapp_client->id, $data);

        $response->assertStatus(200)
        ->assertJsonFragment($data);
    }
}

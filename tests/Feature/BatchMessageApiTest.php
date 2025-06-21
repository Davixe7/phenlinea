<?php

namespace Tests\Feature;

use App\Admin;
use App\BatchMessage;
use App\Extension;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BatchMessageApiTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    /** @test */
    public function can_fetch_messages()
    {
        $admin = Admin::factory()->has(BatchMessage::factory(5), 'batch_messages')->createOne();

        $response = $this
        ->withHeader('Authorization', "Bearer {$admin->api_token}")
        ->get("/api/v2/batch_messages");

        $response
        ->assertStatus(200)
        ->assertJsonFragment(['admin_id'=>1]);
    }

    /** @test */
    public function can_create_a_message()
    {
        $admin = Admin::factory()->has(Extension::factory(200))->create();

        $data = [
            'title'     => 'Mensaje Masivo Test',
            'body'      => 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Dolore debitis maiores ducimus, aperiam sunt, incidunt non minima impedit maxime iusto autem ipsum voluptatibus, hic accusantium magnam dolores nam quas architecto?',
            'receivers' => $admin->extensions()->pluck('id')->toArray()
        ];

        $response = $this
        ->withHeader('Authorization', "Bearer {$admin->api_token}")
        ->post("/api/v2/batch_messages", $data);

        $response
        ->assertStatus(201)
        ->assertJsonFragment(['title'=>$data['title'], 'admin_id'=>$admin->id]);
    }
}

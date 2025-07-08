<?php

namespace Tests\Feature;

use App\Admin;
use App\Novelty;
use App\Porteria;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NoveltyApiTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

     /**  @test */
    public function can_fetch_novelties()
    {
        $admin = Admin::factory(1)
        ->has(Porteria::factory(1)->has(Novelty::factory(10)))
        ->createOne();

        $response = $this
        ->withHeader('Authorization', "Bearer {$admin->api_token}")
        ->get('/api/v2/novelties');

        $response
        ->assertJsonCount(10, 'data')
        ->assertStatus(200);
    }

    /** @test */
    public function can_update_a_novelty(){
        $admin = Admin::factory(1)
        ->has(Porteria::factory(1)->has(Novelty::factory(10)))
        ->createOne();

        $novelty = Novelty::first();
        $data = ['read_at' => now()];

        $response = $this
        ->withHeader('Authorization', "Bearer {$admin->api_token}")
        ->put("/api/v2/novelties/{$novelty->id}", $data);

        $response
        ->assertStatus(200)
        ->assertJsonFragment($data);
    }

    /** @test */
    public function can_delete_a_novelty(){
        $admin = Admin::factory(1)
        ->has(Porteria::factory(1)->has(Novelty::factory(1)))
        ->createOne();

        $novelty = Novelty::first();

        $response = $this
        ->withHeader('Authorization', "Bearer {$admin->api_token}")
        ->delete("/api/v2/novelties/{$novelty->id}");

        $response
        ->assertStatus(204);
    }
}

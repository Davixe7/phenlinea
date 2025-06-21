<?php

namespace Tests\Feature;

use App\Admin;
use App\Petition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PetitionApiTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    /** @test */
    public function can_fetch_petitions()
    {
        $admin = Admin::factory(1)
        ->has(Petition::factory(10))
        ->createOne();

        $response = $this
        ->withHeader('Authorization', "Bearer {$admin->api_token}")
        ->get('/api/v2/petitions');

        $response
        ->assertJsonCount(10, 'data')
        ->assertStatus(200);
    }

    /** @test */
    public function can_show_a_petition()
    {
        $admin = Admin::factory(1)
        ->has(Petition::factory(1))
        ->createOne();

        $petition = Petition::first();

        $response = $this
        ->withHeader('Authorization', "Bearer {$admin->api_token}")
        ->get("/api/v2/petitions/{$petition->id}");

        $response
        ->assertJsonFragment(['admin_id'=>$admin->id, 'status'=>'read'])
        ->assertStatus(200);
    }

    /** @test */
    public function can_update_a_petition()
    {
        $admin = Admin::factory(1)
        ->has(Petition::factory(1))
        ->createOne();

        $petition = Petition::first();
        $data = ['answer'=>'Okay'];

        $response = $this
        ->withHeader('Authorization', "Bearer {$admin->api_token}")
        ->put("/api/v2/petitions/{$petition->id}", $data);

        $response
        ->assertJsonFragment([
            'admin_id' => $admin->id,
            'answer'   => 'Okay',
            'status'   => 'replied'
        ])
        ->assertStatus(200);
    }

    /** @test */
    public function can_delete_a_petition()
    {
        $admin = Admin::factory(1)
        ->has(Petition::factory(1))
        ->createOne();

        $petition = Petition::first();

        $response = $this
        ->withHeader('Authorization', "Bearer {$admin->api_token}")
        ->delete("/api/v2/petitions/{$petition->id}");

        $response
        ->assertStatus(204);
    }
}

<?php

namespace Tests\Feature;

use App\Admin;
use App\Extension;
use App\Resident;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResidentApiTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    /** @test */
    public function can_fetch_residents()
    {
        $admin = Admin::factory(1)
        ->has(Extension::factory(5)->has(Resident::factory(5)))
        ->createOne();

        $response = $this
        ->withHeader('Authorization', "Bearer {$admin->api_token}")
        ->get('/api/v2/residents');

        $response
        ->assertStatus(200)
        ->assertJsonCount(25, 'data');
    }

    /** @test */
    public function can_create_a_resident(){
        $admin = Admin::factory(1)
        ->has(Extension::factory(1))
        ->createOne();

        $data = [
            'extension_id'  => Extension::first()->id,
            'name'          => 'John Doe',
            'age'           => 30,
            'dni'           => '30000000',
            'card'          => '30000000',
            'is_owner'      => true,
            'is_resident'   => true,
            'is_authorized' => true,
            'disability'    => false
        ];

        $response = $this
        ->withHeader('Authorization', "Bearer {$admin->api_token}")
        ->post('/api/v2/residents', $data);

        $response
        ->assertStatus(201)
        ->assertJsonFragment(['card'=>'30000000']);
    }

    /** @test */
    public function can_update_a_resident(){
        $admin = Admin::factory(1)
        ->has(Extension::factory(1)->has(Resident::factory(1)))
        ->createOne();

        $resident = Resident::first();
        $data = ['name' => 'John Updated'];

        $response = $this
        ->withHeader('Authorization', "Bearer {$admin->api_token}")
        ->put("/api/v2/residents/{$resident->id}", $data);

        $response
        ->assertStatus(200)
        ->assertJsonFragment($data);
    }

    /** @test */
    public function can_delete_a_resident(){
        $admin = Admin::factory(1)
        ->has(Extension::factory(1)->has(Resident::factory(1)))
        ->createOne();

        $resident = Resident::first();

        $response = $this
        ->withHeader('Authorization', "Bearer {$admin->api_token}")
        ->delete("/api/v2/residents/{$resident->id}");

        $response
        ->assertStatus(204);
    }
}


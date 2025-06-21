<?php

namespace Tests\Feature;

use App\Admin;
use App\Extension;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ExtensionApiTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    /** @test */
    public function can_create_an_extension()
    {
        $admin = Admin::factory()->createOne();

        $data = [
            'name'        => 1000,
            'owner_name'  => 'John Doe',
            'owner_phone' => '3000000000',
            'phone_1'     => '3000000000',
        ];

        $response = $this
            ->withHeader('Authorization', "Bearer {$admin->api_token}")
            ->post('/api/v2/extensions', $data);

        $response
        ->assertStatus(201)
        ->assertJsonFragment(['name'=>1000]);
    }

    /** @test */
    public function can_fetch_extensions(){
        $admin = Admin::factory()->has(Extension::factory(1000))->createOne();
        $response = $this
            ->withHeader('Authorization', "Bearer {$admin->api_token}")
            ->get('/api/v2/extensions');

        $response
        ->assertStatus(200)
        ->assertJsonCount(1000,'data');
    }

    /** @test */
    public function can_update_an_extension(){
        $admin = Admin::factory()->has(Extension::factory(1))->createOne();
        $id    = $admin->extensions()->first()->id;

        $response = $this
            ->withHeader('Authorization', "Bearer {$admin->api_token}")
            ->put("/api/v2/extensions/{$id}", ['name'=>'999']);

        $response
        ->assertStatus(200)
        ->assertJsonFragment(['name'=>'999']);
    }

    /** @test */
    public function can_delete_an_extension(){
        $admin = Admin::factory()->has(Extension::factory(1))->createOne();
        $id    = $admin->extensions()->first()->id;
        $response = $this
            ->withHeader('Authorization', "Bearer {$admin->api_token}")
            ->delete("/api/v2/extensions/{$id}");

        $response
        ->assertStatus(204);
    }
}
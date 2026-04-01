<?php

namespace Tests\Feature;

use App\Admin;
use App\Extension;
use App\Resident;
use App\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VehicleApiTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    /** @test */
    public function can_fetch_vehicles()
    {
        $admin = Admin::factory(1)
        ->has(Extension::factory(5)
        ->has(Resident::factory(1)->has(Vehicle::factory(5))))
        ->createOne();

        $response = $this
        ->withHeader('Authorization', "Bearer {$admin->api_token}")
        ->get('/api/v2/vehicles');

        $response
        ->assertStatus(200)
        ->assertJsonCount(25, 'data');
    }

    /** @test */
    public function can_create_a_vehicle(){
        $admin = Admin::factory(1)
        ->has(Extension::factory(1)->has(Resident::factory(1)))
        ->createOne();

        $extensionId = Extension::first()->id;
        $residentId  = Resident::first()->id;

        $data = [
            'extension_id'  => $extensionId,
            'resident_id'   => $residentId,
            'type'          => 'car',
            'plate'         => '300000',
            'tag'           => '300000'
        ];

        $response = $this
        ->withHeader('Authorization', "Bearer {$admin->api_token}")
        ->post('/api/v2/vehicles', $data);

        $response
        ->assertStatus(201)
        ->assertJsonFragment($data);
    }

    /** @test */
    public function can_update_a_vehicle(){
        $admin = Admin::factory(1)
        ->has(Extension::factory(1)->has(Resident::factory(1)))
        ->createOne();

        $resident = Resident::first();
        $vehicle  = Vehicle::factory(1, [
            'extension_id'=>$resident->extension_id, 
            'resident_id'=>$resident->id
        ])->createOne();

        $data     = ['plate' => '999999'];

        $response = $this
        ->withHeader('Authorization', "Bearer {$admin->api_token}")
        ->put("/api/v2/vehicles/{$vehicle->id}", $data);

        $response
        ->assertStatus(200)
        ->assertJsonFragment($data);
    }

    /** @test */
    public function can_delete_a_vehicle(){
        $admin = Admin::factory(1)
        ->has(Extension::factory(1)->has(Resident::factory(1)->has(Vehicle::factory(1))))
        ->createOne();

        $vehicle = Vehicle::first();

        $response = $this
        ->withHeader('Authorization', "Bearer {$admin->api_token}")
        ->delete("/api/v2/vehicles/{$vehicle->id}");

        $response
        ->assertStatus(204);
    }
}

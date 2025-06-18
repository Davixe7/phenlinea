<?php

namespace Tests\Feature;

use App\Admin;
use App\Visit;
use App\Visitor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PlatesApiTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    /** @test */
    public function can_fetch_plates()
    {
        Visitor::factory(1)->create();
        $admin = (Admin::factory(1)
        ->has(Visit::factory(100))
        ->create())
        ->first();

        $response = $this->getJson('/api/plates', [
            'Authorization' => "Bearer $admin->api_token"
        ]);

        $response
        ->assertStatus(200)
        ->assertJsonCount(100);
    }
}

<?php

namespace Tests\Feature;

use App\Admin;
use App\Visit;
use App\Visitor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VisitApiTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    /** @test */
    public function can_fetch_visits()
    {
        Visitor::factory(10)->create();
        $admin   = Admin::factory(1)
        ->has(Visit::factory(100,['visitor_id'=>fake()->numberBetween(1,10)]))
        ->createOne();

        $response = $this
        ->withHeader('Authorization', "Bearer {$admin->api_token}")
        ->get("/api/v2/visits");

        $response
        ->assertJsonCount(100, 'data')
        ->assertJsonFragment(['admin_id'=>1])
        ->assertStatus(200);
    }
}

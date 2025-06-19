<?php

namespace Tests\Feature;

use App\Admin;
use App\User;
use App\Visit;
use App\Visitor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PlatesApiTest extends TestCase
{
    use RefreshDatabase;

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

        $user = User::factory(1)->createOne(['email'=>'root@phenlinea.com', 'password'=>bcrypt(123456), 'name'=>'Root']);
        $response = $this
        ->withHeader("Authorization",  "Bearer $user->api_token")->getJson('/api/plates', [
            'Authorization' => "Bearer $admin->api_token"
        ]);

        $response
        ->assertStatus(200)
        ->assertJsonCount(100);
    }
}

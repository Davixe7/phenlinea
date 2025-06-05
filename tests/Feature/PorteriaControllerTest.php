<?php

namespace Tests\Feature;

use App\Porteria;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PorteriaControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    /** @test */
    public function it_can_list_all_porterias()
    {   
        Porteria::factory(3)->create();

        $response = $this->get('/api/v2/admin/porterias');

        $response->assertStatus(200)
                  ->assertJsonCount(3, 'data');
    }

    /** @test */
    public function it_can_create_a_porteria(){
        $porteria = Porteria::factory()->make()->toArray();
        $porteria['password'] = '123456';

        $response = $this->postJson('/api/v2/admin/porterias', $porteria);

        $response->assertStatus(201);
    }

    /** @test **/
    public function it_can_delete_a_porteria(){
        $porteria = Porteria::factory()->create();

        $response = $this->delete('/api/v2/admin/porterias/' . $porteria->id);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('porterias', ['id'=>$porteria->id]);
    }
}

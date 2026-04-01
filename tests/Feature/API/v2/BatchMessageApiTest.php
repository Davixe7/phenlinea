<?php

namespace Tests\Feature\API\v2;

use App\Admin;
use App\BatchMessage;
use App\Extension;
use App\User;
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
        $admin = (Admin::factory(1)->has(Extension::factory(100))->create())->first();
        $messages = BatchMessage::factory(3)->create(['admin_id' => $admin->id]);
        $user = User::factory(1)->createOne(['email'=>'root@phenlinea.com', 'password'=>bcrypt(123456), 'name'=>'Root']);
        $response = $this
        ->withHeader("Authorization",  "Bearer $user->api_token")->get('/api/v2/batch-messages');

        $response
        ->assertStatus(200)
        ->assertJsonCount(3, 'data');
        /* ->assertJsonFragment([
            'data' => ['*'=>['admin_id'=>$admin->id]]
        ]); */
    }
}

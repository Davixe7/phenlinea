<?php

namespace Tests\Feature;

use App\Admin;
use App\BatchMessage;
use App\Extension;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BatchMessageApiTest extends TestCase
{
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
        $response = $this->get('/api/v2/batch-messages');

        $response
        ->assertStatus(200)
        ->assertJsonCount(3, 'data');
        /* ->assertJsonFragment([
            'data' => ['*'=>['admin_id'=>$admin->id]]
        ]); */
    }
}

<?php

namespace Tests\Feature;

use App\Admin;
use App\Invoice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InvoiceApiTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    /** @test */
    public function can_fetch_invoices(){
        $admin = Admin::factory()->has(Invoice::factory(1))->createOne();

        $response = $this
            ->withHeader('Authorization', "Bearer {$admin->api_token}")
            ->get('/api/v2/invoices');

        $response
        ->assertJsonCount(1,'data')
        ->assertJsonStructure([
            'data' => [
                '*' =>['number', 'nit', 'date', 'total', 'status', 'paid_at']
            ]
        ])
        ->assertStatus(200);
    }
}

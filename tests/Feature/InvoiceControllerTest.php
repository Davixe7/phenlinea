<?php

namespace Tests\Feature;

use App\Admin;
use App\Invoice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class InvoiceControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

     /** @test */
    public function it_can_list_invoices()
    {
        Admin::factory(100)->has(
            Invoice::factory()->state(function (array $attributes, Admin $admin) {
                return ['nit' => $admin->nit];
            })
        )->create();

        $response = $this->get('/api/v2/admin/invoices');

        $response->assertStatus(200)
        ->assertJsonCount(100, 'data');
    }

    /** @test */
    public function it_can_import_invoices(){
        Storage::fake('public');

        // Crea un archivo falso con contenido real
        $fileContent = file_get_contents(storage_path('app/example_invoices.xlsx'));
        $file = UploadedFile::fake()->createWithContent('invoices.xlsx', $fileContent);
        $year  = now()->year;
        $month = now()->month;

        // Realiza la solicitud POST al endpoint de subida
        $response = $this->post('/api/v2/admin/invoices', [
            'file'  => $file,
            'year'  => $year,
            'month' => $month
        ]);


        $response->assertStatus(200)->assertExactJson(['data'=>141]);
    }
}

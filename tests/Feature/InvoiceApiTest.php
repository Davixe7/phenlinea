<?php

namespace Tests\Feature;

use App\Admin;
use App\Invoice;
use Database\Factories\InvoiceFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Rap2hpoutre\FastExcel\FastExcel;
use Tests\TestCase;

class InvoiceApiTest extends TestCase
{
    /* use RefreshDatabase; */

    /** @test */
    public function can_fetch_all_invoices()
    {
        Admin::factory(100)->has(Invoice::factory(1))->create();
        $response = $this->get('/api/v2/invoices');

        $response
            ->assertStatus(200)
            ->assertJsonCount(100, 'data')
            ->assertJsonStructure([
                'data' => ['*' => ['number', 'date', 'nit', 'total', 'status', 'paid_at']]
            ]);
    }

    /** @test */
    public function can_upload_invoices()
    {
        DB::table('admins')->delete();
        $path     = storage_path('app/test/nodups.xlsx');
        $invoices = (new FastExcel())->import($path);
        $admins = Admin::factory($invoices->count())
        ->make()
        ->each(function ($admin, $index) use ($invoices) {
            $admin->nit = $invoices[$index]['Número Identificación Cliente'];
            $admin->password = bcrypt('password');
            $admin->address = "Somewhere";
        });
        Admin::insert($admins->toArray());

        // Simular almacenamiento
        //Storage::fake('local');

        // Cargar el archivo real desde la carpeta de pruebas
        $file = new UploadedFile(
            storage_path('app/test/invoice.xlsx'), // Ruta real del archivo
            'ejemplo.xls',
            'application/vnd.ms-excel',
            null, // Tamaño opcional
            true   // Test mode, evita errores de seguridad
        );

        // Hacer la petición de subida
        $response = $this->postJson('/api/v2/invoices', [
            'file' => $file,
            'month' => now()->month,
            'year' => now()->year,
        ]);

        $response
        ->assertStatus(200)
        ->assertJsonFragment(['data'=>141]);
    }

    /** @test */
    function can_update_invoice(){
        $newInvoice = (Invoice::factory()->create())->first();
        $response = $this->putJson("/api/v2/invoices/{$newInvoice->id}");

        $response
        ->assertStatus(200);
    }
}

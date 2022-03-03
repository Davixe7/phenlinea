<?php

namespace App\Http\Controllers;

use App\Invoice;
use App\Clarisa;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class InvoiceController extends Controller
{
    public function index()
    {
      $invoices = auth()->user()->invoices()->orderBy('created_at', 'DESC')->get();
      return view('admin.invoices.index', ['invoices' => $invoices]);
    }

    public function show(Invoice $invoice)
    {
      $pdf = Clarisa::getInvoicePDF( $invoice->number );
      return response()
            ->streamDownload(function() use ($pdf) { echo base64_decode( $pdf );},
              'phenlinea_factura.pdf',
              ['Content-Type' => 'application/pdf']
            );
    }
}

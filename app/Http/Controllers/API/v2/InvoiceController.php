<?php

namespace App\Http\Controllers\API\v2;

use App\Invoice;
use App\Clarisa;
use App\Http\Controllers\Controller;

class InvoiceController extends Controller
{
    public function index()
    {
      $invoices = auth()->user()->invoices()->with('admin')->orderBy('created_at', 'DESC')->get();
      $invoices = $invoices->map(function($i){
          $i->status = $i->paid_at
          ? 'pagado'
          : (($i->date < now()->startOfMonth()) ? 'vencido' : 'pendiente');
          return $i;
      });
      return response()->json(['data'=>$invoices]);
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

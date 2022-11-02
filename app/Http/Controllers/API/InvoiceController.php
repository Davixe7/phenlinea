<?php

namespace App\Http\Controllers\API;

use App\Invoice;
use App\Http\Controllers\Controller;
use App\Http\Resources\Invoice as InvoiceResource;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
  public function index()
  {
    $invoices = Invoice::orderBy('created_at')->get();
    return response()->json(['data' => $invoices]);
  }

  public function search(Request $request)
  {
    $invoices = Invoice::where('nit', $request->nit)
      ->where('status', 'pendiente')
      ->orderBy('created_at')
      ->with('admin')->get();
      
    $invoices = InvoiceResource::collection( $invoices );

    if (count($invoices) < 1) {
      abort('404', 'No hay facturas pendientes de pago para el nit provisto');
    }

    return response()->json(['data' => $invoices]);
  }

  public function show(Invoice $invoice)
  {
    return response()->json(['data' => $invoice]);
  }

  public function pay(Invoice $invoice)
  {
    $invoice->update([
      'paid_at' => Carbon::now(),
      'status'  => 'pagado'
    ]);
    return response()->json(['data' => $invoice]);
  }
}

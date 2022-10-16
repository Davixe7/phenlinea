<?php

namespace App\Http\Controllers\API;

use App\Invoice;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class InvoiceController extends Controller
{
  public function index()
  {
    $invoices = Invoice::orderBy('created_at')->get();
    return response()->json(['data' => $invoices]);
  }

  public function show(Invoice $invoice)
  {
    return response()->json(['data' => $invoice]);
  }

  public function pay(Invoice $invoice)
  {
    $invoice->update(['paid_at' => Carbon::now()]);
    return response()->json(['data' => $invoice]);
  }
}

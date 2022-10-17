<?php

namespace App\Http\Controllers\API;

use App\Invoice;
use App\Http\Controllers\Controller;
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
    $invoice = Invoice::where('nit', $request->nit)->where('date', $request->date . '-01')->orderBy('created_at')->firstOrFail();
    return response()->json(['data' => $invoice]);
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

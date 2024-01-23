<?php 
namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Admin;
use Illuminate\Http\Request;

class ResidentInvoiceController extends Controller {
  
  public function balance(Request $request){
    $admin      = Admin::whereNit($request->nit)->firstOrFail();
    $extension  = $admin->extensions()->whereName( $request->apto )->firstOrFail();

    $start_date = $request->startdate ?: now()->startOfMonth()->format('Y-m-d');
    $end_date   = $request->enddate   ?: now()->endOfDay()->format('Y-m-d');
    $invoices   = $extension->resident_invoices()
                  ->whereBetween('created_at', [$start_date, $end_date])
                  ->with('resident_invoice_payments')
                  ->get();

    $total      = $invoices->reduce(fn(float $carry, $invoice) => $carry + $invoice->pending, 0);
    $data       = compact('extension', 'invoices', 'total', 'start_date', 'end_date');

    return view('public.resident-invoices.balance', $data);
  }
}
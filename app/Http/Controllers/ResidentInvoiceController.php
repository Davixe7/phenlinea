<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Extension;
use App\Imports\ResidentInvoiceImport;
use App\ResidentInvoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ResidentInvoiceController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request, ?Extension $extension)
  {
    if( auth()->guest() ){
      $request->validate([
        'nit'  => 'required',
        'apto' => 'required'
      ]);

      $admin     = Admin::whereNit($request->nit)->firstOrFail();
      $extension = $admin->extensions()->whereName($request->apto)->firstOrFail();

    }

    if( $extension ){
      $resident_invoices = $extension->resident_invoices;
      return view('admin.extensions.invoices', compact('extension', 'resident_invoices'));
    }
    $resident_invoices = auth()->user()->resident_invoices;
    return view('admin.resident-invoices.index', compact('resident_invoices'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function upload()
  {
    return view('admin.resident-invoices.import');
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show(ResidentInvoice $resident_invoice)
  {
    $resident_invoice->load('resident_invoice_batch.admin');
    return view('public.resident-invoices.show', compact('resident_invoice'));
  }

  public function import(Request $request)
  {
    $request->validate(['file' => 'file|max:5000|mimes:xls,xlsx']);
    $file = $request->file('file');
    $path = $file->store('resident-invoices');
    Excel::import(new ResidentInvoiceImport($request->all()), $path);
    return response()->json(['data' => 'Success']);
  }

  public function download(ResidentInvoice $resident_invoice)
  {
    $download = request()->download;
    $pdf = Pdf::loadView('pdf.resident-invoice', compact('resident_invoice', 'download'));
    return $pdf->download('invoice.pdf');
  }

  public function balance(Request $request, Extension $extension){
    $start_date = $request->startdate ?: now()->startOfMonth()->format('Y-m-d');
    $end_date   = $request->enddate   ?: now()->addDays(1)->endOfDay()->format('Y-m-d');

    $invoices   = $extension->resident_invoices()
                  ->whereBetween('created_at', [$start_date, $end_date])
                  ->with('resident_invoice_payments')
                  ->get();

    $total      = $invoices->reduce(fn(float $carry, $invoice) => $carry + $invoice->pending, 0);
    $data       = compact('extension', 'invoices', 'total', 'start_date', 'end_date');

    return $request->download
    ? Pdf::loadView('pdf.cuenta', $data)->download('edo-cta.pdf')
    : view('admin.extensions.balance', $data);
  }
}

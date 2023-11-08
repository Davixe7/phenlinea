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
    $pdf = Pdf::loadView('pdf.factura', ['resident_invoice' => $resident_invoice]);
    return $pdf->download('invoice.pdf');
  }

  public function apartmentInvoices(Request $request){
    $request->validate(['nit'=>'required', 'apto'=>'required']);
    $admin     = Admin::whereNit($request->nit)->firstOrFail();
    $apto      = $admin->extensions()->whereName( $request->apto )->firstOrFail();
    $invoices  = $apto->resident_invoices;
    return view('public.resident-invoices.index', compact('invoices'));
  }
}

<?php

namespace App\Http\Controllers;

use App\Imports\ResidentInvoiceImport;
use App\Imports\ResidentInvoiceUpdateImport;
use App\ResidentInvoiceBatch;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ResidentInvoiceBatchController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $resident_invoice_batches = auth()->user()->resident_invoice_batches()->withCount('resident_invoices')->get();
    return view('admin.resident-invoice-batches.index', compact('resident_invoice_batches'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function upload()
  {
    return view('admin.resident-invoice-batches.upload');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function import(Request $request)
  {
    $request->validate(['file' => 'file|max:5000|mimes:xls,xlsx']);

    $batch = ResidentInvoiceBatch::create(array_merge(['admin_id'=>auth()->id()], $request->except('file')));

    $file = $request->file('file');
    $path = $file->store('resident-invoices');
    Excel::import(new ResidentInvoiceImport($batch), $path);
    return response()->json([
      'count' => $batch->resident_invoices()->count()
    ]);
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\ResidentInvoiceBatch  $residentInvoiceBatch
   * @return \Illuminate\Http\Response
   */
  public function show(ResidentInvoiceBatch $residentInvoiceBatch)
  {
    $residentInvoiceBatch->load('resident_invoices.resident_invoice_payments');
    return view('admin.resident-invoice-batches.show', ['resident_invoice_batch' => $residentInvoiceBatch]);
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\ResidentInvoiceBatch  $residentInvoiceBatch
   * @return \Illuminate\Http\Response
   */
  public function edit(ResidentInvoiceBatch $residentInvoiceBatch)
  {
    return view('admin.resident-invoice-batches.upload', ['batch'=>$residentInvoiceBatch]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\ResidentInvoiceBatch  $residentInvoiceBatch
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, ResidentInvoiceBatch $residentInvoiceBatch)
  {
    $request->validate(['file' => 'file|max:5000|mimes:xls,xlsx']);
    $file = $request->file('file');
    $path = $file->store('resident-invoices');

    Excel::import(new ResidentInvoiceUpdateImport($residentInvoiceBatch), $path);
    return response()->json([
      'count' => $residentInvoiceBatch->resident_invoices()->count()
    ]);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\ResidentInvoiceBatch  $residentInvoiceBatch
   * @return \Illuminate\Http\Response
   */
  public function destroy(ResidentInvoiceBatch $residentInvoiceBatch)
  {
    $residentInvoiceBatch->delete();
    return redirect()->route('resident-invoice-batches.index')
           ->with('message', 'Lote y sus facturas eliminados exitosamente');
  }
}

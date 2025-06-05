<?php

namespace App\Http\Controllers\API\v2\Admin;

use App\Invoice;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Rap2hpoutre\FastExcel\FastExcel;

class InvoiceController extends Controller
{
  public function index(Request $request)
  {
    $year  = $request->year  ?: Carbon::now()->year;
    $month = $request->month ?: Carbon::now()->month;

    $invoices = Invoice::inMonth($month, $year)->with('admin')->get()->sortBy('admin.name');

    return response()->json(['data' => $invoices]);
  }

  public function update(Invoice $invoice, Request $request)
  {
    $invoice->update([
      'paid_at'        => $request->status == 'pagado' ? now() : null,
      'status'         => $request->status,
      'payment_method' => 'super'
    ]);
    return response()->json(['data' => $invoice->load('admin')]);
  }

  public function store(Request $request)
  {

    $request->validate([
      'file'  => 'required|file|max:5000',
      'month' => 'required|min:1|max:12',
      'year'  => 'required',
    ]);

    $date = $request->year . '-' . str_pad($request->month, 2, '0', STR_PAD_LEFT) . '-01';
    
    if( Invoice::where('date', $date)->count() ){
        DB::table('invoices')->where('date', $date)->delete();
    }

    if (!Storage::exists($folder = 'public/invoices')) {
      Storage::makeDirectory($folder);
    }

    $path = $request->file->store($folder);
    $path = storage_path("app/$path");

    $facturas = (new FastExcel)->import($path, function ($line) use ($date) {
      $nit = null;
      if (!$line['Número Documento']) {
        return;
      }

     $nit = $line['Número Identificación Cliente'];
     
      return Invoice::create([
        'number' => $line['Número Documento'],
        'nit'    => $nit,
        'date'   => $date,
        'total'  => $line['Total'],
      ]);
    });

    $count = $facturas->count();
    return response()->json(['data' => $count]);
  }
}

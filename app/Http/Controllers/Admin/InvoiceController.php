<?php

namespace App\Http\Controllers\Admin;

use App\Invoice;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Rap2hpoutre\FastExcel\FastExcel;

class InvoiceController extends Controller
{
  public function upload(Request $request)
  {
    $year  = $request->year  ?: Carbon::now()->year;
    $month = $request->month ?: Carbon::now()->month;

    $monthsName = [
      "Enero",
      "Febrero",
      "Marzo",
      "Abril",
      "Mayo",
      "Junio",
      "Julio",
      "Agosto",
      "Septiembre",
      "Octubre",
      "Noviembre",
      "Diciembre"
    ];

    $invoices = Invoice::inMonth($month, $year)->with('admin')->get()->sortBy('admin.name');

    if ($request->expectsJson()) {
      return response()->json(['data' => $invoices]);
    }

    return view('super.invoices.import', compact('invoices', 'month', 'year', 'monthsName'));
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

  public function import(Request $request)
  {

    $request->validate([
      'file'  => 'required|file|max:5000',
      'month' => 'required|min:1|max:12',
      'year'  => 'required',
    ]);

    $date = $request->year . '-' . $request->month . '-01';
    
    if( Invoice::where('date', $date)->count() ){
        DB::table('invoices')->where('date', $date)->delete();
    }

    if (!Storage::exists($folder = 'public/invoices')) {
      Storage::makeDirectory($folder);
    }

    $path = $request->file->store($folder);
    $path = storage_path("app/$path");

    $facturas = (new FastExcel)->import($path, function ($line) use ($date) {
      if (!$line['Número Factura']) {
        return;
      }
      return Invoice::create([
        'number' => $line['Número Factura'],
        'nit'    => $line['ID Cliente'],
        'date'   => $date,
        'total'  => $line['Total'],
      ]);
    });

    if ($request->expectsJson()) {
      return response()->json(['data' => 'ok']);
    }

    if ($count = $facturas->count()) {
      return redirect()->route('admin.invoices.upload')->with(['message' => "$count Facturas importadas con éxito"]);
    }

    return redirect()->route('admin.invoices.upload');
  }
}

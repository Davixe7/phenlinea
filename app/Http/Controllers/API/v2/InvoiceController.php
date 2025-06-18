<?php

namespace App\Http\Controllers\API\v2;

use App\Invoice;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Exception;
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

    $invoices = Invoice::inMonth($month, $year)->with('admin')->get();

    return response()->json(['data' => $invoices]);
  }

  public function store(Request $request)
  {
    $request->validate([
      'file'  => 'required|file|max:5000',
      'month' => 'required|min:1|max:12',
      'year'  => 'required',
    ]);

    $date = Carbon::create($request->year, $request->month);
    DB::table('invoices')->where('date', $date)->delete();

    $folder = 'public/invoices';
    Storage::makeDirectory($folder);
    $path = $request->file->store($folder);
    $path = storage_path("app/$path");

    try {
      $facturas = (new FastExcel)->import($path, function ($line) use ($date) {
        if (!$line['Número Documento']) {
          return;
        }

        return Invoice::create([
          'nit'    => $line['Número Identificación Cliente'],
          'number' => $line['Número Documento'],
          'total'  => $line['Total'],
          'date'   => $date
        ]);
      });
      return response()->json(['data' => $facturas->count()]);
    } catch (Exception $e) {
      return response()->json(['message'=>$e->getMessage(), 500]);
    }
  }

  public function update(Invoice $invoice, Request $request)
  {
    $invoice->update([
      'paid_at'        => $request->status == 'pagado' ? now() : null,
      'status'         => $request->status,
      'payment_method' => 'super'
    ]);
    $invoice->paid_at = $invoice->paid_at->format('Y-m-d H:i:s');
    return response()->json(['data' => $invoice->load('admin')]);
  }
}

<?php

namespace App\Http\Controllers\Admin\v2;

use App\Invoice;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\MessageBag;
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

  public function validateFile(Request $request){
    $request->validate([
      'file'  => 'required|file|max:5000',
      'month' => 'required|min:1|max:12',
      'year'  => 'required',
    ]);

    $index = -1;
    $count = 0;
    $errors = new MessageBag();

    if (!Storage::exists($folder = 'public/invoices')) {
      Storage::makeDirectory($folder);
    }

    $path = $request->file->store($folder);
    $path = storage_path("app/$path");

    (new FastExcel)->import($path, function ($line) use (&$count, &$errors, &$index) {
      $index++;
      $number = isset($line['Número Documento']) ? $line['Número Documento'] : null;
      $nit    = isset($line['Número Identificación Cliente']) ? $line['Número Identificación Cliente'] : null;

      if (!$number || !$nit) {
        $errors->add("lines[{$index}]", "La fila no contiene NIT o Número de Factura");
        return;
      }
      $count++;
    });

    $errors->count()
    ? $errors->add('file', 'El archivo subido no incluye facturas validas o falta alguna de las siguientes columnas: [Número Documento] [Número Identificación Cliente]')
    : null;

    if( $count == 0 ){
      return response()->json(compact('errors'), 422);
    }

    return response()->json(['data'=>compact('count', 'errors')], 200);
  }

  public function store(Request $request)
  {
    $request->validate([
      'file'  => 'required|file|max:5000',
      'month' => 'required|min:1|max:12',
      'year'  => 'required',
    ]);

    $count = 0;
    $month = str_pad($request->month, 2, '0', STR_PAD_LEFT);
    $date = $request->year . '-' . $month . '-01';

    if (Invoice::where('date', $date)->count()) {
      DB::table('invoices')->where('date', $date)->delete();
    }

    if (!Storage::exists($folder = 'public/invoices')) {
      Storage::makeDirectory($folder);
    }

    $path = $request->file->store($folder);
    $path = storage_path("app/$path");

    (new FastExcel)->import($path, function ($line) use ($date, &$count) {
      $data = [
        'number' => isset($line['Número Documento']) ? $line['Número Documento'] : null,
        'nit'    => isset($line['Número Identificación Cliente']) ? $line['Número Identificación Cliente'] : null,
        'total'  => isset($line['Total']) ? $line['Total'] : null,
        'date'   => $date
      ];

      if (!$data['number'] || !$data['nit'] ) { return; }

      $count++;
      return Invoice::create($data);
    });

    return response()->json(['data' => ['count'=>$count]]);
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
}

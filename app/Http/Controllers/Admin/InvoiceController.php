<?php

namespace App\Http\Controllers\Admin;

use App\Invoice;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Rap2hpoutre\FastExcel\FastExcel;

class InvoiceController extends Controller
{
  public function upload(){
    return view('super.invoices.import');
  }

  public function import(Request $request){
    $request->validate([
      'file' => 'required|file|max:5000'
    ]);

    if( !Storage::exists( $folder = 'public/invoices' ) ){
      Storage::makeDirectory($folder);
    }

    $path = $request->file->store($folder);
    $path = storage_path("app/$path");

    $facturas = (new FastExcel)->import($path, function ($line) use ($request) {
      if( !$line['Número Factura'] ){ return; }
      return Invoice::create([
        'number'=> $line['Número Factura'],
        'nit'   => $line['ID Cliente'],
        'date'  => $request->periodo,
        'total' => $line['Total'],
      ]);
    });

    if( $count = $facturas->count() ){
      return redirect()->route('admin.invoices.upload')->with(['message' => "$count Facturas importadas con éxito"]);
    }

    return redirect()->route('admin.invoices.upload');
  }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\Factura as FacturaResource;
use Illuminate\Support\Facades\Storage;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Factura;
use App\Admin;

class FacturaController extends Controller
{

  public function __construct()
  {
    $this->middleware('modules:residents_billing');
  }
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $user = auth()->user();
    if ($user && $user->admin_id) {
      $facturas = Factura::where('apto', $user->name)->where('admin_id', $user->admin_id)->orderBy('created_at', 'DESC')->get();
      return view('resident.facturas', ['facturas' => $facturas]);
    } else {
      $facturas = Factura::all();
      return response()->json(['data' => $facturas]);
      return view('admin.facturas', ['facturas' => $facturas]);
    }
  }

  public function byCode(Request $request)
  {
    if (!$request->expectsJson()) {
      $admins = Admin::all();
      return view('public.factura', ['admins' => $admins]);
    }

    if ($bill_code = $request->bill_code) {
      $array_code = explode("-", $bill_code);
      if (!is_array($array_code) || count($array_code) < 2) {
        return response()->json(['data' => 'Formato incorrecto'], 404);
      }

      $admin = $array_code[0];
      $apto  = $array_code[1];

      $factura = Factura::where('admin_id', $admin)
        ->where('apto', $apto)
        ->orderBy('created_at', 'DESC')
        ->firstOrFail();
      return response()->json(['data' => $factura]);
    }
    return response()->json(['data' => 'Falta el código']);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function upload()
  {
    return view('admin.facturas.import');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function import(Request $request)
  {
    $request->validate([
      'file' => 'required|file|max:5000'
    ]);

    $previous = auth()->user()->facturas()->wherePeriodo($request->periodo)->get();
    if ($previous) {
      $previous->each(function ($f) {
        $f->delete();
      });
    }

    if (!Storage::exists($folder = 'public/facturas')) {
      Storage::makeDirectory($folder);
    }

    $path = $request->file->store($folder);
    $path = storage_path("app/$path");

    $dates = [
      'periodo' => $request->periodo,
      'emision' => $request->emision,
      'limite'  => $request->limite,
      'note'    => $request->note
    ];

    $facturas = (new FastExcel)->import($path, function ($line) use ($dates) {
      $importes = [];
      $base = [
        'apto'      => $line['apto'],
        'periodo'   => $dates['periodo'],
        'emision'   => $dates['emision'],
        'limite'    => $dates['limite'],
        'admin_id'  => auth()->user()->id,
        'link'      => request()->link,
        'note'      => $dates['note'],
      ];

      for ($i = 1; $i < 7; $i++) {
        $importes["concepto{$i}"] = $line["concepto{$i}"];
        $importes["vencido{$i}"]  = $line["vencido{$i}"];
        $importes["actual{$i}"]   = $line["actual{$i}"];
      }

      $content = array_merge($importes, $base);

      return Factura::create($content);
    });

    if ($count = $facturas->count()) {
      return view('admin.facturas.import')->with(['message' => "$count Facturas importadas con éxito"]);
    }

    return view('admin.facturas.import');
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show(Factura $factura)
  {
    return view('admin.facturas.show', [
      'factura' => $factura
    ]);
  }

  public function download(Factura $factura)
  {
    $pdf = \PDF::loadView('pdf.factura', ['factura' => $factura]);
    return $pdf->download('invoice.pdf');
  }
}

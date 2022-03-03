<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Extension;
use App\Http\Resources\ExtensionsExport;
use App\Http\Resources\ResidentExport;
use Rap2hpoutre\FastExcel\FastExcel;
use Rap2hpoutre\FastExcel\SheetCollection;
use App\Admin;

class ExportController extends Controller
{
  public function exportCensus(Admin $admin){
    $extensions = ExtensionsExport::collection( $admin->extensions )->toArray(true);
    $residents  = ResidentExport::collection( $admin->residents )->toArray(true);
    
    $collection = new SheetCollection([
      'Extensiones' => $extensions,
      'Residentes'  => $residents
    ]);
    
    return (new FastExcel( $collection ))->download("phln_" . time() . ".xlsx");
    
    // (new FastExcel( Extension::limit(100)->get() ))->export('extensions2.xlsx', function($user){
    //   return [
    //     'id' => $user->id,
    //     'linea 1' => $user->phone_1,
    //     'linea 2' => $user->phone_2,
    //     'linea 3' => $user->phone_3,
    //     'linea 4' => $user->phone_4,
    //     'tel_propietario' => $user->owner_phone,
    //     'mascotas' => $user->pets_count,
    //     'parqueadero_1' => $user->parking_number1,
    //     'parqueadero_2' => $user->parking_number2,
    //     'vehiculos' => $user->vehicles,
    //     'deposito' => $user->has_deposit
    //   ];
    // });
  }
  
  public function exportAdmins(){
    return view('super.admins.export', ['admins'=>Admin::all()]);
  }
}

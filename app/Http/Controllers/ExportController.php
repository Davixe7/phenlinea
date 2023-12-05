<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\ExtensionsExport;
use App\Http\Resources\ResidentExport;
use Rap2hpoutre\FastExcel\FastExcel;
use Rap2hpoutre\FastExcel\SheetCollection;
use App\Admin;
use App\Http\Resources\ZhyafExtension;
use DB;

class ExportController extends Controller
{
  public function exportCensus(Admin $admin = null){
      
    if( !$admin && auth()->user()->nit ){
        $admin = auth()->user();
    }
    
    $extensions = ZhyafExtension::collection( $admin->extensions )->toArray(true);
    $residents  = ResidentExport::collection( $admin->residents )->toArray(true);
    
    $collection = new SheetCollection([
      'Extensiones' => $extensions,
      'Residentes'  => $residents
    ]);
    
    return (new FastExcel( $collection ))->download("phln_" . time() . ".xlsx");
  }
  
  public function exportAdmins(){
    return view('super.admins.export', ['admins'=>Admin::all()]);
  }
}

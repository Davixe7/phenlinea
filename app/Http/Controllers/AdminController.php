<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Http\Resources\Admin as AdminResource;
use App\Http\Resources\Payment as PaymentResource;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExtensionsExport;

class AdminController extends Controller
{

  public function __construct(){
    $this->authorizeResource(Admin::class, 'admin');
  }

  public function show(Admin $admin)
  {
    return new AdminResource( $admin );
  }
  
  public function payments(Request $request, Admin $admin)
  {
    return PaymentResource::collection($admin->payments);
  }
  
  public function exportExtensions(Request $request, Admin $admin){
    return Excel::download(new ExtensionsExport, 'extensiones.xlsx');
  }
}

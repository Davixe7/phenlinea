<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\Payment;
use App\Http\Requests\StoreAdmin as StoreAdminRequest;
use App\Http\Resources\Admin as AdminResource;
use App\Http\Resources\Payment as PaymentResource;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExtensionsExport;
use App\Http\Controllers\Controller;
use App\Traits\Uploads;
use Illuminate\Support\Str;

class AdminController extends Controller
{
  use Uploads;
  
  public function __construct(){
    $this->authorizeResource(Admin::class, 'admin');
  }

  public function index()
  {
    $admins = Admin::orderBy('name', 'asc')->get();
    return view('super.admins.index', compact('admins'));
  }

  public function list()
  {
    $admins = Admin::without('extensions')->orderBy('name', 'ASC')->get();
    return AdminResource::collection( $admins );
  }

  public function create()
  {
    return view('admins.create');
  }

  public function store(StoreAdminRequest $request)
  { 
    $admin = Admin::create([
      'contact_email'      => $request->contact_email,
      'nit'      => $request->nit,
      'name'     => $request->name,
      'phone'    => $request->phone,
      'phone_2'  => $request->phone_2,
      'address'  => $request->address,

      'email'      => $request->email,
      'password'   => bcrypt( $request->password )
    ]);
    
    if( $admin ){
      $year = date('Y') . '-01-01';
      $admin->payments()->save( new Payment(['year'=>$year]) );
    }

    return new AdminResource( $admin );
  }

  public function show(Admin $admin)
  {
    return new AdminResource( $admin );
  }

  public function edit(Admin $admin)
  {
    return view('admins.edit', ['admin'=>$admin]);
  }

  public function update(Request $request, Admin $admin)
  {
    $request->validate([
      'nit' => 'required|unique:admins,nit,' . $admin->id
    ]);

    $admin->update([
      'device_community_id' => ($request->device_community_id) ?: $admin->device_community_id,
      'device_serial_number' => ($request->device_serial_number) ?: $admin->device_serial_number,
      'visits_lifespan' => ($request->visits_lifespan) ?: $admin->visits_lifespan,
      'name'     => ($request->name) ?: $admin->name,
      'phone'    => ($request->phone) ?: $admin->phone,
      'phone_2'  => ($request->phone_2) ?: $admin->phone_2,
      'nit'      => ($request->nit) ?: $admin->nit,
      'address'  => ($request->address) ?: $admin->address,
      'email'    => ($request->email) ?: $admin->email,
      'password' => ($request->password) ? bcrypt($request->password) : $admin->password,
      'contact_email' => ($request->contact_email) ?: $admin->contact_email,
      'status'        => $request->status ?: $admin->status,
      'slug'          => Str::slug($request->name) ?: $admin->slug,
      'whatsapp_group_id' => $request->whatsapp_group_id,
      'whatsapp_group_url' => $request->whatsapp_group_url
    ]);

    if( $file = $request->file('whatsapp_qr') ){
      $admin->addMedia($file)->toMediaCollection('whatsapp_qr');
    }

    return new AdminResource( $admin );
  }

  public function destroy(Admin $admin)
  {
    $admin->delete();
    return response()->json(['message'=>'Admin deleted successfuly']);
  }
  
  public function getPayments(Request $request, Admin $admin){
    $payments = $admin->payments;
    return PaymentResource::collection($payments);
  }
  
  public function exportExtensions(Request $request, Admin $admin){
    return Excel::download(new ExtensionsExport, 'extensiones.xlsx');
  }

  public function editPermissions(Request $request, Admin $admin){
    $modules = \App\Module::all();
    return view('super.admins.edit-permissions',[
      'admin'   => $admin,
      'modules' => $modules
    ]);
  }

  public function updatePermissions(Request $request, Admin $admin){
    $admin->modules()->sync( $request->modules );
    return redirect()->route('admin.admins.edit-permissions', ['admin'=>$admin->id]);
  }
}


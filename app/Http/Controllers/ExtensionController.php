<?php

namespace App\Http\Controllers;

use App\Extension;
use App\Admin;
use App\Http\Requests\StoreExtension as StoreExtensionRequest;
use App\Http\Resources\Extension as ExtensionResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ExtensionsImport;
use App\Events\SendPasswordSms;

class ExtensionController extends Controller
{
  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function __construct(){
    $this->authorizeResource(Extension::class, 'extension');
  }
  
  public function index()
  {
    $this->authorize('index', Extension::class);
    return view('admin.census.index');
  }
  
  public function list(Request $request)
  {
    if( Auth::guard('web')->check() ){
      $extensions = Extension::unidad( $request->name )->get();
    }else{
      $extensions = auth()->user()->extensions;
    }
    
    // $this->authorize('index', Extension::class);
    return ExtensionResource::collection( $extensions );
  }
  
  
  /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function create()
  {
    return view('extensions.create');
  }
  
  /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
  public function store(StoreExtensionRequest $request)
  {
    
    $extension = Extension::create([
      'name'     => $request->name,
      'phone_1'  => $request->phone_1,
      'phone_2'  => $request->phone_2,
      'admin_id' => auth()->user()->id
    ]);
    
    return new ExtensionResource( $extension->load('admin') );
  }
  
  /**
  * Display the specified resource.
  *
  * @param  \App\Extension  $extension
  * @return \Illuminate\Http\Response
  */
  public function show(Extension $extension)
  {
    return new ExtensionResource( $extension );
  }
  
  /**
  * Show the form for editing the specified resource.
  *
  * @param  \App\Extension  $extension
  * @return \Illuminate\Http\Response
  */
  public function edit(Extension $extension)
  {
    return view('extensions.edit', ['extension'=>$extension]);
  }
  
  /**
  * Update the specified resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @param  \App\Extension  $extension
  * @return \Illuminate\Http\Response
  */
  public function update(StoreExtensionRequest $request, Extension $extension)
  {    
    $extension->update([
      'name'    => $request->name,
      'phone_1' => $request->phone_1,
      'phone_2' => $request->phone_2,
    ]);
    
    return new ExtensionResource( $extension->load('admin') );
  }
  
  /**
  * Remove the specified resource from storage.
  *
  * @param  \App\Extension  $extension
  * @return \Illuminate\Http\Response
  */
  public function destroy(Extension $extension)
  {
    $extension->delete();
    return response()->json(['message'=>'Extension deleted successfully']);
  }
  
  public function resetPassword(Request $request, Extension $extension){
    $extension->resetPassword();
    return new ExtensionResource( $extension );
  }
  
  public function sendPasswordSms(Request $request, Extension $extension){
    $extension->resetPassword();
    $extension->passwordResetPhone = $request->phone;
    event( new SendPasswordSms( $extension ) );
    return response()->json(['data'=>'Password sent']);
  }
  
  public function getImport(Request $request){
    $admins = Admin::all();
    return view('extensions.import', ['admins'=>$admins]);
  }
  
  public function import(Request $request) {
    $request->validate([
      'batch' => 'required|mimes:xls,xlsx|max:5000'
    ]);
    
    try{
      $path = $request->batch->store('imports');
      Excel::import(new ExtensionsImport, $path);
      
      $errors = ( session()->get('validation.errors') ) ?: [];
      session()->forget('validation.errors');
      
      return response()->json(['errors'=>$errors]);
    }catch (\Exception $e) {
      return response()->json(["message"=>"Error on import " . $e->getMessage()], 400);
    }
  }
}

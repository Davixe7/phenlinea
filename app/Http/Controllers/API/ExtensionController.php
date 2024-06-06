<?php

namespace App\Http\Controllers\API;

use App\Extension;
use App\Http\Resources\Extension as ExtensionResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ExtensionController extends Controller
{

  public function __construct(){
      $this->authorizeResource(Extension::class, 'extension');
  }

  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function index(Request $request)
  {
    $extensions = auth()->user()->extensions()
                       ->phone( $request->phone )
                       ->name( $request->name )->get();
    return ExtensionResource::collection( $extensions );
  }

  public function residents(Request $request, Extension $extension){
    return $extension->residents;
  }

  /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
  public function store(Request $request)
  {
    $extension = Extension::create([
      'name'    => $request->name,
      'phone_1' => $request->phone_1,
      'phone_2' => $request->phone_2,
      'phone_3' => $request->phone_3,
      'phone_4' => $request->phone_4,
      'admin_id' => auth()->user()->id,
      '_email' => auth()->user()->id . $request->name . '@phenlinea.com',
      '_password' => bcrypt(123456),
      'password' => bcrypt(123456),
    ]);
    return new ExtensionResource( $extension );
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
  * Update the specified resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @param  \App\Extension  $extension
  * @return \Illuminate\Http\Response
  */
  public function update(Request $request, Extension $extension)
  {
    $extension->update([
      'name'    => ($request->name)    ?: $extension->name,
      'phone_1' => ($request->phone_1) ?: $extension->phone_1,
      'phone_2' => $request->phone_2   ?: $extension->phone_2,
      'phone_3' => $request->phone_3   ?: $request->phone_3,
      'phone_4' => $request->phone_4   ?: $request->phone_4
    ]);

    return new ExtensionResource( $extension );
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
    return response()->json(['message'=>"Extension $extension->id deleted successfully"]);
  }
  
  public function byphone(Request $request){
    if( $request->phone ){
     $ext = Extension::phone( $request->phone )->get();
     if( count($ext) ){
        return ExtensionResource::collection( $ext );         
     }
    }
    abort(404, 'No hay extensión con el número asignado');
  }
  
  public function resetPassword(Request $request, Extension $extension){
    $extension->resetPassword();
    return new ExtensionResource( $extension );
  }
  
}

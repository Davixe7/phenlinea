<?php

namespace App\Http\Controllers\Admin\v2;

use App\Porteria;
use Illuminate\Http\Request;
use App\Http\Resources\Porteria as PorteriaResource;
use App\Http\Requests\StorePorteria as StorePorteriaRequest;
use App\Http\Controllers\Controller;

class PorteriaController extends Controller
{ 
  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  
  public function index()
  {
    $porterias = Porteria::orderBy('created_at', 'DESC')->with('admin')->get();
    return PorteriaResource::collection($porterias);
  }
  
  /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
  public function store(StorePorteriaRequest $request)
  {
    $data = $request->validated();
    $data['password'] = bcrypt( $request->password );
    $porteria = Porteria::create($data);
    
    return new PorteriaResource($porteria);
  }
  
  /**
  * Display the specified resource.
  *
  * @param  \App\Porteria  $porteria
  * @return \Illuminate\Http\Response
  */
  public function show(Porteria $porteria)
  {
    return new PorteriaResource( $porteria );
  }
  
  /**
  * Update the specified resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @param  \App\Porteria  $porteria
  * @return \Illuminate\Http\Response
  */
  
  public function update(Request $request, Porteria $porteria)
  {
    $data = $request->validate([
      'name'     => 'required|between:4,50',
      'email'    => 'required|unique:porterias,email,'.$porteria->id,
      'password' => 'nullable|between:6,24'
    ]);
    
    $porteria->update($data);
    
    return new PorteriaResource($porteria);
  }
  
  /**
  * Remove the specified resource from storage.
  *
  * @param  \App\Porteria  $porteria
  * @return \Illuminate\Http\Response
  */
  public function destroy(Porteria $porteria)
  {
    $porteria->delete();
    return response()->json([], 204);
  }
}

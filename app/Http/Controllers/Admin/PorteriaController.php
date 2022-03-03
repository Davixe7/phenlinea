<?php

namespace App\Http\Controllers\Admin;

use App\Porteria;
use Illuminate\Http\Request;
use App\Http\Resources\Porteria as PorteriaResource;
use App\Http\Requests\StorePorteria as StorePorteriaRequest;
use App\Http\Controllers\Controller;

class PorteriaController extends Controller
{
  public function __construct(){
    $this->authorizeResource(Porteria::class, 'porteria');
  }
  
  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  
  public function index()
  {
    $this->authorize('index', Porteria::class);
    return view('super.porterias.index');
  }
  
  public function list(Request $request)
  {
    $this->authorize('index', Porteria::class);
    $porterias = Porteria::all();
    return PorteriaResource::collection( $porterias->load('admin:id,name') );
  }
  
  /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function create()
  {
    return view('porterias.create');
  }
  
  /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
  public function store(StorePorteriaRequest $request)
  {
    $porteria = Porteria::create([
      'name'     => $request->name,
      'email'    => $request->email,
      'password' => bcrypt( $request->password ),
      'admin_id' => $request->admin_id
    ]);
    
    return new PorteriaResource( $porteria->load('admin') );
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
  * Show the form for editing the specified resource.
  *
  * @param  \App\Porteria  $porteria
  * @return \Illuminate\Http\Response
  */
  public function edit(Porteria $porteria)
  {
    return view('porterias.edit', ['porteria'=>$porteria]);
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
    $request->validate([
      'name'     => 'required|between:4,50',
      'email'    => 'required|email',
      'password' => 'nullable|between:6,24'
    ]);
    
    $porteria->update([
      'name'     => $request->name,
      'email'    => $request->email,
      'password' => $request->password ? bcrypt( $request->password ) : $porteria->password
    ]);
    
    return new PorteriaResource( $porteria->load('admin') );
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
    return response()->json(['message'=>'Succesful deletion']);
  }
}

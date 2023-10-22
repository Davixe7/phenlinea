<?php

namespace App\Http\Controllers;

use App\Extension;
use App\Resident;
use App\Http\Requests\StoreResident as StoreResidentRequest;
use App\Http\Resources\Resident as ResidentResource;
use App\Traits\Devices;
use Illuminate\Http\Request;

class ResidentController extends Controller
{
  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function __construct(){
    
  }

  public function index(Extension $extension)
  {
    $extension_id = $extension->id;
    $residents    = $extension->residents;
    return view('admin.residents.index', compact('residents', 'extension'));
  }

  public function list(Request $request)
  {
    // $this->authorize('index', Resident::class);
    $residents = $request->user()->residents;
    return ResidentResource::collection( $residents );
  }


  /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function create()
  {
    return view('residents.create');
  }

  /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */

  public function store(StoreResidentRequest $request)
  {
    $resident = Resident::create([
      'extension_id'    => $request->extension_id,
      'name'            => $request->name,
      'email'           => $request->email,
      'age'             => $request->age,
      'dni'             => $request->dni,
      'is_owner'        => $request->is_owner,
      'is_resident'     => $request->is_resident,
      'is_authorized'   => $request->is_authorized,
      'disability'      => $request->disability,
      'card'            => $request->card,
      'device_synced'   => false
    ]);

    if( $picture = $request->file('picture') ){
      $resident->addMedia( $picture )->toMediaCollection('picture');
    }

    $devices = new Devices();
    $devices->addResident( $resident );

    return new ResidentResource( $resident );
  }

  /**
  * Display the specified resource.
  *
  * @param  \App\Resident  $resident
  * @return \Illuminate\Http\Response
  */
  public function show(Resident $resident)
  {
    return new ResidentResource( $resident );
  }

  /**
  * Show the form for editing the specified resource.
  *
  * @param  \App\Resident  $resident
  * @return \Illuminate\Http\Response
  */
  public function edit(Resident $resident)
  {
    return view('residents.edit', ['resident' => $resident]);
  }

  /**
  * Update the specified resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @param  \App\Resident  $resident
  * @return \Illuminate\Http\Response
  */
  public function update(StoreResidentRequest $request, Resident $resident)
  {
    $request->validate([
      'name' => 'required',
      'dni'  => 'required',
    ]);

    $resident->update([
      'name'            => $request->name,
      'email'           => $request->email,
      'age'             => $request->age,
      'dni'             => $request->dni,
      'is_owner'        => $request->is_owner,
      'is_resident'     => $request->is_resident,
      'is_authorized'   => $request->is_authorized,
      'disability'      => $request->disability,
      'card'            => $request->card,
      'device_synced'   => false
    ]);

    if( $picture = $request->file('picture') ){
      $resident->addMedia( $picture )->toMediaCollection('picture');
    }

    $devices = new Devices();
    $devices->updateResident($resident);

    return new ResidentResource( $resident );
  }

  /**
  * Remove the specified resource from storage.
  *
  * @param  \App\Resident  $resident
  * @return \Illuminate\Http\Response
  */
  public function destroy(Resident $resident)
  {
    $resident->delete();
    $devices = new Devices();
    $devices->deleteResident($resident);
    return response()->json(['message'=>'Resident deleted successfuly']);
  }
}

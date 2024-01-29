<?php

namespace App\Http\Controllers;

use App\Extension;
use App\Resident;
use App\Http\Requests\StoreResident as StoreResidentRequest;
use App\Http\Resources\Resident as ResidentResource;
use App\Traits\Devices;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

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
    $request->validate([
      'card' => 'nullable|unique:residents'
    ]);

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

    $picturePath = null;

    if( $picture = $request->file('picture') ){
      $resident->addMedia( $picture )->toMediaCollection('picture');
      $picturePath = $resident->getFirstMediaPath('picture');
    }

    
    if( auth()->user()->device_building_id ){
      $devices = new Devices();
      try {
        $devices->addResident( $resident, $picturePath );
      }
      catch(Exception $e){
        $resident->delete();
        abort(422, 'Error al sincronizar con plataforma de dispositivos');
      }
    }
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
      'card' => [
        'nullable',
        Rule::unique('residents')->ignore($resident->id)
      ]
    ]);

    $resident->name          = $request->name;
    $resident->email         = $request->email;
    $resident->age           = $request->age;
    $resident->dni           = $request->dni;
    $resident->is_owner      = $request->is_owner;
    $resident->is_resident   = $request->is_resident;
    $resident->is_authorized = $request->is_authorized;
    $resident->disability    = $request->disability;
    $resident->card          = $request->card;

    $path = $request->file('picture') ? $request->file('picture')->getPathName() : null;

    if( auth()->user()->device_building_id ){
      try {
        $devices = new Devices();
        $devices->updateResident($resident, $path);
      }
      catch( Exception $e ){
        abort(500, 'Error al sincronizar con el servidor de dispositivos');
      }
    }

    if( $path ) $resident->addMedia($path)->toMediaCollection('picture');
    $resident->save();
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
    if( !auth()->user()->device_community_id ){
      $resident->delete();
      return response()->json(['message'=>'Resident deleted successfuly']);
    }

    try {
      $devices = new Devices();
      $devices->deleteResident($resident);
      $resident->delete();
      return response()->json(['message'=>'Resident deleted successfuly']);
    }
    catch( Exception $e ){
      abort(500, $e->getMessage());
    }
  }
}

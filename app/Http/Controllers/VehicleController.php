<?php

namespace App\Http\Controllers;

use App\Extension;
use App\Traits\Devices;
use App\Vehicle;
use Exception;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Extension $extension)
    {
      $vehicles = $extension->vehicles;
      $extension->load('residents');
      return view('admin.vehicles.index', compact('extension', 'vehicles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Extension $extension)
    {
      $extension->load('residents');
      return view('admin.vehicles.create', compact('extension'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Extension $extension, Request $request)
    {
        $vehicle = Vehicle::create($request->all());
        if( auth()->user()->device_community_id ){
          $devices = new Devices();
          if( $resident = $vehicle->resident ){
            $devices->updateResident( $resident, null );
          }
        }
        return $vehicle;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function show(Vehicle $vehicle)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function edit(Vehicle $vehicle)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function update(Extension $extension, Vehicle $vehicle, Request $request)
    {
        $request->validate([
          'resident_id' => 'required'
        ]);
        
        $vehicle->update($request->all());
        if( auth()->user()->device_community_id ){
          $devices = new Devices();
          $devices->updateResident($vehicle->resident, null);
        }
        return $vehicle;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vehicle $vehicle)
    {
      try {
        $vehicle->delete();
        $devices = new Devices();
        $devices->updateResident($vehicle->resident, null);
        return $vehicle;
      } catch (Exception $e) {
        abort(422, $e->getCode() . " " . $e->getMessage());
      }
    }
}

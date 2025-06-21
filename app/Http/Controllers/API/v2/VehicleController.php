<?php

namespace App\Http\Controllers\API\v2;

use App\Http\Controllers\Controller;
use App\Vehicle;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VehicleController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $vehicles = Vehicle::filterExtension($request->extension_id)->get();
    return response()->json(['data' => $vehicles]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $data = $request->validate([
      'extension_id' => 'required|exists:extensions,id',
      'resident_id'  => 'required|exists:residents,id',
      'type'         => 'required|string',
      'plate'        => 'required|string',
      'tag'          => 'required|string',
    ]);

    try {
      $vehicle = Vehicle::create($data);
      if ($resident = $vehicle->resident) {
        $resident->zhyafUpdate();
      }
      return response()->json(['data' => $vehicle], 201);
    } catch (Exception $e) {
      $vehicle->delete();
      abort(500, $e->getMessage());
    }
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Vehicle  $vehicle
   * @return \Illuminate\Http\Response
   */
  public function update(Vehicle $vehicle, Request $request)
  {
    $data = $request->validate([
      'resident_id' => 'sometimes|exists:residents,id',
      'type'        => 'sometimes|string',
      'plate'       => 'sometimes|string',
      'tag'         => 'sometimes|string',
    ]);

    try {
      DB::transaction(function () use ($vehicle, $data) {
        $vehicle->update($data);
        if ($vehicle->resident) {
          $vehicle->resident->zhyafUpdate();
        }
      });
      return response()->json(['data' => $vehicle]);
    } catch (Exception $e) {
      abort(522, $e->getMessage());
    }
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
      DB::transaction(function () use ($vehicle) {
        $vehicle->delete();
        if ($vehicle->resident) {
          $vehicle->resident->zhyafUpdate();
        }
      });
      return response()->json([], 204);
    } catch (Exception $e) {
      abort(422, $e->getCode() . " " . $e->getMessage());
    }
  }
}

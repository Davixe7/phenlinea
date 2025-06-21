<?php

namespace App\Http\Controllers\API\v2;

use App\Resident;
use App\Http\Controllers\Controller;
use App\Http\Resources\Resident as ResidentResource;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Validation\ValidationException;

class ResidentController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */

  public function index(Request $request)
  {
    $residents = Resident::all();
    return ResidentResource::collection($residents);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */

  public function store(Request $request)
  {
    $resident = null;
    $data = $request->validate([
      'extension_id'  => 'required|exists:extensions,id',
      'name'          => 'required|string',
      'age'           => 'required|numeric',
      'dni'           => 'required|string',
      'is_owner'      => 'required|boolean',
      'is_resident'   => 'required|boolean',
      'is_authorized' => 'required|boolean',
      'disability'    => 'nullable|boolean',
      'card'          => 'nullable|string|unique:residents,card',
    ]);

    try {
      $resident = Resident::create($data);
      $path = request()->file('picture') ? request()->file('picture')->getPathName() : null;
      $resident->zhyafCreate($path);
      $resident->grantAccessToAllDoors();
      if($path){
        $resident->addMedia($path)->toMediaCollection('picture');
      }
      return new ResidentResource($resident);
    }
    catch (Exception $e) {
      if ($resident) {
        $resident->delete();
      }

      if ($e->getCode() == 10000) {
        throw ValidationException::withMessages(['card' => [$e->getMessage()]]);
      }

      abort(500, $e->getMessage());
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Resident  $resident
   * @return \Illuminate\Http\Response
   */
  public function show(Resident $resident)
  {
    return new ResidentResource($resident);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Resident  $resident
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Resident $resident)
  {
    $data = $request->validate([
      'card'          => 'sometimes|unique:residents,card',
      'name'          => 'required|string',
      'age'           => 'sometimes|numeric',
      'dni'           => 'sometimes|string',
      'is_owner'      => 'sometimes|boolean',
      'is_resident'   => 'sometimes|boolean',
      'is_authorized' => 'sometimes|boolean',
      'disability'    => 'nullable|boolean',
    ]);

    try {
      $resident->update($data);
      return new ResidentResource($resident);
    } catch (\Throwable $th) {
      abort(500, $th->getMessage());
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Resident  $resident
   * @return \Illuminate\Http\Response
   */
  public function destroy(Resident $resident)
  {
    try {
      $resident->delete();
      return response()->json([], 204);
    }
    catch(Exception $e){
      abort(500, $e->getCode() . ' ' . $e->getMessage());
    }
  }
}

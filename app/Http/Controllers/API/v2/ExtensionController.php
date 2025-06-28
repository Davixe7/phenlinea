<?php

namespace App\Http\Controllers\API\v2;

use App\Extension;
use App\Http\Resources\Extension as ExtensionResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class ExtensionController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $extensions = auth()->user()->extensions()
      ->phone($request->phone)
      ->name($request->name)->get();

    return ExtensionResource::collection($extensions);
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
      'name' => 'required',
      'owner_name'        => 'nullable',
      'owner_phone'       => 'nullable',
      'email'             => 'nullable|email',
      'emergency_contact' => 'nullable',
      'observation'       => 'nullable|string',

      'pets_count'        => 'nullable|numeric',
      'deposit'           => 'nullable',
      'has_own_parking'   => 'nullable',
      'parking_number1'   => 'nullable',
      'parking_number2'   => 'nullable',

      'phone_1' => 'required|min:10',
      'phone_2' => 'nullable|min:10',
      'phone_3' => 'nullable|min:10',
      'phone_4' => 'nullable|min:10',
    ]);

    try {
      $extension = auth()->user()->extensions()->create($data);
      return new ExtensionResource($extension);
    }
    catch (Exception $e) {
      return response()->json([
        'code'    => $e->getCode(),
        'message' => $e->getMessage()
      ], 500);
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Extension  $extension
   * @return \Illuminate\Http\Response
   */
  public function show(Extension $extension)
  {
    return new ExtensionResource($extension);
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
    $data = $request->validate([
      'name'              => 'required',
      'owner_name'        => 'nullable',
      'owner_phone'       => 'nullable',
      'email'             => 'nullable|email',
      'emergency_contact' => 'nullable',
      'observation'       => 'nullable|string',

      'pets_count'        => 'nullable|numeric',
      'deposit'           => 'nullable',
      'has_own_parking'   => 'nullable',
      'parking_number1'   => 'nullable',
      'parking_number2'   => 'nullable',

      'phone_1' => 'sometimes|min:10',
      'phone_2' => 'nullable|min:10',
      'phone_3' => 'nullable|min:10',
      'phone_4' => 'nullable|min:10',
    ]);

    $extension->update($data);

    return new ExtensionResource($extension);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Extension  $extension
   * @return \Illuminate\Http\Response
   */
  public function destroy(Extension $extension)
  {
    try {
      $extension->delete();
      return response()->json([], 204);
    }
    catch (Exception $e) {
      return response()->json([
        'code'    => $e->getCode(),
        'message' => $e->getMessage()
      ], 500);
    }
  }
}

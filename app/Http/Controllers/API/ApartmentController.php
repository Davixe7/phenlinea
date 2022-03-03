<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Resources\ApartmentPorteria;
use App\Http\Controllers\Controller;

class ApartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $apartments = auth()->user()->extensions()->get();
      return ApartmentPorteria::collection( $apartments );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
      $apartment = auth()->user()->extensions()->whereName($request->apartment)->first();
      if( !$apartment ){
        abort(404, 'No existe el apartamento');
      }
      return response()->json(['data'=>$apartment]);
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Porteria;
use Illuminate\Http\Request;
use App\Http\Resources\Porteria as PorteriaResource;
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
        $porterias = PorteriaResource::collection( Porteria::all() );
        return $porterias;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Porteria  $porteria
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Porteria $porteria)
    {
      $porteria->update([
        'name'     => $request->name,
        'email'    => $request->email,
        'password' => bcrypt( $request->password )
      ]);
      
      return new PorteriaResource( $porteria );
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
        return response()->json(['message'=>'Porteria deleted succesfully']);
    }
}

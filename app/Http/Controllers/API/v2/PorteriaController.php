<?php

namespace App\Http\Controllers\API\v2;

use App\Http\Controllers\Controller;
use App\Http\Resources\Porteria as ResourcesPorteria;
use App\Porteria;
use Illuminate\Http\Request;

class PorteriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $porterias = Porteria::with('admin')->get();
        return ResourcesPorteria::collection($porterias);
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
            'email' => 'required|email|unique:porterias,email',
            'password' => 'required|min:8',
            'admin_id' => 'required'
        ]);

        $porteria = Porteria::create($data);
        return new ResourcesPorteria($porteria);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Porteria  $porteria
     * @return \Illuminate\Http\Response
     */
    public function show(Porteria $porteria)
    {
        return new ResourcesPorteria($porteria);
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
            'name' => 'sometimes|required',
            'email' => "sometimes|required|email|unique:porterias,email,$porteria->id",
            'password' => 'sometimes|required|string|min:8',
        ]);

        $porteria->update($data);
        return new ResourcesPorteria($porteria->load('admin'));
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

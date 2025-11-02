<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\WhatsappInstance;
use Illuminate\Http\Request;

class WhatsappInstanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = WhatsappInstance::all();
        return response()->json(compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate(['instance_id'=>'required', 'label'=>'required', 'phone'=>'required']);
        $whatsappInstance = WhatsappInstance::create($data);
        return response()->json(compact('data'), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\WhatsappInstance  $whatsappInstance
     * @return \Illuminate\Http\Response
     */
    public function show(WhatsappInstance $whatsappInstance)
    {
        return response()->json(['data'=>$whatsappInstance]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\WhatsappInstance  $whatsappInstance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WhatsappInstance $whatsappInstance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\WhatsappInstance  $whatsappInstance
     * @return \Illuminate\Http\Response
     */
    public function destroy(WhatsappInstance $whatsappInstance)
    {
        //
    }
}

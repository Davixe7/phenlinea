<?php

namespace App\Http\Controllers;

use App\Visitor;
use App\Extension;
use Illuminate\Http\Request;

class VisitorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Extension $extension)
    {
        $visitors = $extension->visitors;
        $checkins = $extension->checkins;
        
        return view('admin.extensions.visitors', compact('visitors', 'checkins', 'extension'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $visitor = Visitor::create([
            'extension_id' => $request->extension_id,
            'name' => $request->name,
            'dni'  => $request->dni,
            'plate' => $request->plate,
            'authorized_at' => $request->authorized_at,
        ]);
        
        return redirect()->route('visitors.index', ['extension'=>$request->extension_id])->with(['message'=>'Registro creado exitosamente']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Visitor  $visitor
     * @return \Illuminate\Http\Response
     */
    public function show(Visitor $visitor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Visitor  $visitor
     * @return \Illuminate\Http\Response
     */
    public function edit(Visitor $visitor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Visitor  $visitor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Visitor $visitor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Visitor  $visitor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Visitor $visitor)
    {
        $visitor->delete();
        return redirect()->route('visitors.index', ['extension'=>$visitor->extension_id])->with(['message'=>'Registro eliminado exitosamente']);
    }
}

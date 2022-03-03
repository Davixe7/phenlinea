<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\VisitorResource;
use App\Visitor;
use App\Extension;

class VisitorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['data'=>auth()->user()->visitors]);
    }
    
    public function extensionVisitors(Extension $extension){
        $visitors = $extension->visitors()->where('authorized_at', '>=', now()->format('Y-m-d') )->get();
        return response()->json(['data'=>$visitors]);
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
            'extension_id' => auth()->id(),
            'name' => $request->name,
            'dni'  => $request->dni,
            'plate' => $request->plate,
            'authorized_at' => $request->authorized_at
        ]);
        
        return response()->json(['data'=>$visitor]);
    }
    
    /**
     * Show the related resource as json
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Visitor $visitor)
    {
        return new VisitorResource( $visitor );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Visitor $visitor)
    {
        $visitor->update([
            'name' => $request->name ?: $visitor->name,
            'dni'  => $request->dni ?: $visitor->dni,
            'plate' => $request->plate ?: $visitor->plate,
            'authorized_at' => $request->authorized_at ?: $visitor->authorized_at
        ]);
        
        return response()->json(['data'=>$visitor]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Visitor $visitor)
    {
        $visitor->delete();
        return response()->json(['data'=>$visitor]);
    }
}

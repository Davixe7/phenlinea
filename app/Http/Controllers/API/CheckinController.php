<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Checkin;
use App\Extension;
use App\Visitor;

class CheckinController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return response()->json(['data'=>auth()->user()->checkins()->with('visitor')->get()]);
    }
    
    public function extensionCheckins(Extension $extension){
        if( $extension->admin_id != auth()->user()->admin_id ){
            abort('403', 'Unauthorized');
        }
        return response()->json(['data'=>$extension->checkins()->with('visitor')->get()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $extension_id = $request->extension_id ?: Visitor::findOrFail( $request->visitor_id )->extension_id;
        //$extension_id = $request->extension_id;
        $checkin = Checkin::create([
            'visitor_id'     => $request->visitor_id,
            'extension_id'   => $extension_id
        ]);
        
        return response()->json(['data'=> $checkin->load('visitor') ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

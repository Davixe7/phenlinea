<?php

namespace App\Http\Controllers;

use App\Classified;
use App\Http\Resources\Ad as ClassifiedCollection;
use Illuminate\Http\Request;

class AdController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $file = \Storage::get('public/colombia.json');
      $states = json_decode( $file, true );
      
      $ads = Classified::state( $request->state )->city( $request->city )->with('pictures')->get();
      
      if( $request->expectsJson() ){
        return new ClassifiedCollection( $ads );
      }
      return view('public.ads', ['ads'=>$ads, 'states'=>$states]);
    }
}

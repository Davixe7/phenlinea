<?php

namespace App\Http\Controllers\Admin;

use App\Classified;
use App\Attachment;
use App\Traits\Uploads;
use App\Http\Resources\Classified as ClassifiedCollection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClassifiedController extends Controller
{
    use Uploads;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $file = \Storage::get('public/colombia.json');
      $states = json_decode( $file, true );
      
      $ads = Classified::state( $request->state )->city( $request->city )->get();
      if( $request->expectsJson() ){
        return new ClassifiedCollection( $ads );
      }
      return view('super.classifieds.index', ['ads'=>$ads, 'states'=>$states]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
      $file = \Storage::get('public/colombia.json');
      $states = json_decode( $file, true );
      
      if( $request->expectsJson() ){
        return response()->json(['data'=>$states]);
      }
      return view('super.classifieds.create', ['states'=>$states]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $ad = Classified::create([
        'name'        => $request->name,
        'description' => $request->description,
        'price'       => $request->price,
        'state'       => $request->state,
        'city'        => $request->city,
        'address'     => $request->address,
        'email'       => $request->email,
        'phone_1'     => $request->phone_1,
        'phone_2'     => $request->phone_2
      ]);
      
      $uploadedPictures = $this->upload($request, 'pictures');
      Attachment::createAll($uploadedPictures, 'classified_id', $ad->id);
      
      return response()->json(['data'=>$ad]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Classified  $ad
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Classified $ad)
    {
      $file = \Storage::get('public/colombia.json');
      $states = json_decode( $file, true );
      
      if( $request->expectsJson() ){
        return response()->json(['states'=>$states, 'ad'=>$ad]);
      }
      return view('super.classifieds.edit', ['states'=>$states, 'ad'=>$ad->load('pictures')]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Classified  $ad
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Classified $ad)
    {  
      $ad->update([
        'name'        => $request->name ?: $ad->name,
        'description' => $request->description ?: $ad->description,
        'price'       => $request->price ?: $ad->price,
        'state'       => $request->state ?: $ad->state,
        'city'        => $request->city  ?: $ad->city,
        'address'     => $request->address ?: $ad->address,
        'email'       => $request->email   ?: $ad->email,
        'phone_1'     => $request->phone_1 ?: $ad->phone_1,
        'phone_2'     => $request->phone_2 ?: $ad->phone_2
      ]);
      
      $uploadedPictures = $this->upload($request, 'pictures');
      Attachment::createAll($uploadedPictures, 'classified_id', $ad->id);
      
      return response()->json(['data'=>$ad->load('pictures')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Classified  $ad
     * @return \Illuminate\Http\Response
     */
    public function destroy(Classified $ad)
    {
      $ad->delete();
      response()->json(['data'=>'Classified deleted successfully']);
    }
}

<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Petition as PetitionResource;
use App\Http\Requests\StorePetition;
use App\Petition;
use App\Traits\Uploads;

class PetitionController extends Controller
{
  use Uploads;
  
  public function __construct(){
    $this->authorizeResource(Petition::class, 'petition');
  }
  
  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function index()
  {
    return PetitionResource::collection( auth()->user()->petitions );
  }
  
  /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
  public function store(StorePetition $request)
  {
    $petition = Petition::create([
      'title'        => $request->title,
      'description'  => $request->description,
      'phone'        => $request->phone,
      'email'        => $request->email,
      'pictures'     => $this->upload($request, 'pictures'),
      'extension_id' => auth()->user()->id
    ]);
    
    return new PetitionResource( $petition );
  }
  
  /**
  * Display the specified resource.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function show(Petition $petition)
  {
    return new PetitionResource( $petition );
  }
  
  /**
  * Update the specified resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function update(Request $request, Petition $petition)
  {
    $uploadedPictures = $this->upload($request, 'pictures');
    $petition->update([
      'title'        => $request->title       ?: $request->title,
      'description'  => $request->description ?: $request->description,
      'phone'        => $request->phone       ?: $request->phone,
      'email'        => $request->email    ?: $request->email,
      'status'       => $request->status   ?: $petition->status,
      'pictures'     => array_merge($uploadedPictures, $petition->pictures)
    ]);
    
    return new PetitionResource( $petition );
  }
  
  /**
  * Remove the specified resource from storage.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function destroy(Petition $petition)
  {
    $petition->delete();
    return response()->json(['data'=>'Petition ' . $petition->id . ' deleted successfuly']);
  }
}

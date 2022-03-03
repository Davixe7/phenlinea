<?php

namespace App\Http\Controllers\API;

use App\Store;
use App\Http\Controllers\Controller;
use App\Http\Resources\Store as StoreResource;
use Illuminate\Http\Request;
use App\Traits\Uploads;

class StoreController extends Controller
{
  use Uploads;
  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function index(Request $request)
  {
    return StoreResource::collection( Store::all() );
  }
  
  /**
  * Display the specified resource.
  *
  * @param  \App\Store  $store
  * @return \Illuminate\Http\Response
  */
  public function show(Store $store)
  {
    return new StoreResource( $store );
  }
  
  public function update(Request $request, Store $store)
  {
    $uploadedLogo = $this->upload($request, 'logo');
    $uploadedLogo = array_shift( $uploadedLogo );
    $updatedPictures = $this->upload($request, 'pictures');
    
    $store->update([
      'nit'       => $request->nit ?: $store->nit,
      'name'      => $request->name ?: $store->name,
      'description' => $request->description ?: $store->description,
      'phone_1'   => $request->phone_1 ?: $store->phone_1,
      // 'phone_2'   => $request->phone_2 ?: $store->phone_2,
      'email'     => $request->email ?:  $store->email,
      
      'lat'       => $request->lat ?: $store->lat,
      'lng'       => $request->lng ?: $store->lng,
      'address'   => $request->address ?: $store->address,
      
      'logo'      => $uploadedLogo ?: $store->logo,
      'pictures'  => array_merge( $updatedPictures, $store->pictures ),
      'category'  => $request->category ?: $store->category,
      'schedule'  => json_decode($request->schedule) ?: $store->schedule
    ]);
    
    return new StoreResource( $store );
  }
}

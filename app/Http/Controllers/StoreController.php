<?php

namespace App\Http\Controllers;

use App\Store;
use Illuminate\Http\Request;
use App\Traits\Uploads;
use App\Http\Resources\Store as StoreResource;
use Illuminate\Support\Facades\Hash;

class StoreController extends Controller
{
  use Uploads;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $stores = Store::all();
      return view('public.stores', ['stores'=>$stores]);
    }
    
    public function show(Store $store)
    {
      $store = $store->load('menu.pictures');
      $menu = $store->menu;
      return view('public.menu', ['store'=>$store,'menu'=>$menu]);
    }
    
    public function update(Store $store, Request $request)
    {
      $uploadedLogo = $this->upload($request, 'logo');
      $uploadedLogo = array_shift( $uploadedLogo );
      $updatedPictures = $this->upload($request, 'pictures');
      
      $store->update([
        'nit'       => $request->nit ?: $store->nit,
        'name'      => $request->name ?: $store->name,
        'description' => $request->description ?: $store->description,
        'phone_1'   => $request->phone_1 ?: $store->phone_1,
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
    
    public function deletepicture(Request $request, Store $store){
      $pictures = collect($store->pictures)->filter(function($p) use ($request) {
        return $p['path'] != $request->picture;
      });
      $store->pictures = $pictures;
      $store->save();
      return response()->json(['data'=>$pictures->toArray()]);
    }
    
    public function resetPassword(Store $store, Request $request){
      $request->validate([
        'old_password'     => [
          function($field,$value,$fail){
            if( !Hash::check( $value, auth()->user()->password ) ){
              $fail('La contraseña actual es incorrecta');
            }
          }
        ],
        'password'              => 'min:6|max:16|confirmed',
        'password_confirmation' => 'same:password',
      ]);
      $store->update(['password'=>bcrypt($request->password),'_password'=>$request->password]);
      return response()->json(['data'=>$store]);
    }
}

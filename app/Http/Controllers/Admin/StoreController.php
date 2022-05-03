<?php

namespace App\Http\Controllers\Admin;

use App\Store;
use App\Http\Controllers\Controller;
use App\Http\Resources\Store as StoreResource;
use Illuminate\Http\Request;
use App\Http\Requests\StoreStore;

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
      if( $request->expectsJson() ){
        return StoreResource::collection( Store::all() );
      }
      return view('super.stores.index', ['stores' => Store::all() ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return view('super.stores.create');
    }
    
    /**
     * Show the form for editing a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Store $store)
    {
      return view('super.stores.edit', ['store'=>$store]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $uploadedLogo = $this->upload($request, 'logo');
      $uploadedLogo = array_shift( $uploadedLogo );
      $password = mt_rand(100000000000, 999999999999) . '';
      $store = Store::create([
        'nit'       => $request->nit,
        'name'      => $request->name,
        'description' => $request->description,
        'phone_1'   => $request->phone_1,
        'email'     => $request->email,
        '_email'     => $request->email,
        'address'   => $request->address,
        'lat'       => $request->lat,
        'lng'       => $request->lng,
      
        'logo'      => $uploadedLogo,
        'pictures'  => $this->upload($request, 'pictures'),
        'category'  => $request->category,
        'schedule'  => json_decode($request->schedule),
        'password'  => bcrypt($password),
        '_password' => $password
      ]);
      
      return redirect()->route('admin.stores.edit', $store->id);
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Store  $store
     * @return \Illuminate\Http\Response
     */
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
      
      return redirect()->route('admin.stores.edit', $store->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function destroy(Store $store)
    {
      $store->delete();
      return response()->json(['data'=>'Store deleted succesfully']);
    }
    
    public function deletepicture(Request $request, Store $store){
      $pictures = collect($store->pictures)->filter(function($p) use ($request) {
        return $p['path'] != $request->picture;
      });
      $store->pictures = $pictures;
      $store->save();
      return response()->json(['data'=>$pictures->toArray()]);
    }
    
    public function updateStatus(Store $store, Request $request){
      $store->status = $store->status != 'active' ? 'active' : 'suspended';
      $store->save();
      return new StoreResource( $store );
    }
    
    public function resetPassword(Store $store){
        $password = mt_rand(100000000000, 999999999999) . '';
        $store->update([
            'password' => bcrypt($password),
            '_password' => $password
        ]);
        return response()->json(['data'=>$password]);
    }
}

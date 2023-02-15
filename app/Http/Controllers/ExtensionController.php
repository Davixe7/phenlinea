<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Extension;
use App\Http\Requests\StoreExtension as StoreExtensionRequest;
use App\Http\Resources\Census as CensusResource;

class ExtensionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $extensions = auth()->user()->extensions()->orderBy('name')->get();
      return view('admin.extensions.index', compact('extensions'));
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
      return view('admin.extensions.create');
    }

    public function edit(Extension $extension){
      return view('admin.extensions.edit', ['extension_id'=>$extension->id]);
    }
    
    public function show(Extension $extension){
        return new CensusResource( $extension );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreExtensionRequest $request)
    {
      $password = mt_rand(100000000000, 999999999999) . '';

      $extension = Extension::create([
        'name'             => $request->name,

        '_email'           => auth()->user()->id . $request->name . '@phenlinea.com',
        '_password'        => $password,
        'password'         => bcrypt( $password ),

        'phone_1'          => $request->phone_1,
        'phone_2'          => $request->phone_2,
        'phone_3'          => $request->phone_3,
        'phone_4'          => $request->phone_4,

        'email'            => $request->email,
        'owner_phone'      => $request->owner_phone,
        'owner_name'       => $request->owner_name,
        'emergency_contact'=> $request->emergency_contact,
        'emergency_contact_name'=> $request->emergency_contact_name,
        
        'pets_count'       => $request->pets_count ?: 0,
        'deposit'          => $request->deposit,
        
        'has_own_parking'  => $request->has_own_parking,
        'parking_number1'  => $request->parking_number1,
        'parking_number2'  => $request->parking_number2,
        
        'observation'      => $request->observation,
        'vehicles'         => $request->vehicles,

        'admin_id'         => auth()->user()->id
      ]);

      return new CensusResource( $extension );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Extension $extension)
    {
      $extension->update([
        'name'             => ($request->name) ?: $extension->name,
        'email'            => ($request->email) ?: $extension->email,
        'phone_1'          => ($request->phone_1) ?: $extension->phone_1,
        'phone_2'          => $request->phone_2,
        'phone_3'          => $request->phone_3,
        'phone_4'          => $request->phone_4,
        'owner_phone'      => $request->owner_phone,
        'owner_name'       => $request->owner_name,
        'emergency_contact'=> $request->emergency_contact,
        'emergency_contact_name'=> $request->emergency_contact_name,
        'pets_count'       => $request->pets_count,
        'has_deposit'      => ($request->has_deposit != $extension->has_deposit) ? $request->has_deposit : $extension->has_deposit,
        'has_own_parking'  => ($request->has_own_parking != $extension->has_own_parking) ? $request->has_own_parking : $extension->has_own_parking,
        'parking_number1'  => $request->parking_number1,
        'parking_number2'  => $request->parking_number2,
        'vehicles'         => $request->vehicles,
        'observation'      => $request->observation
      ]);

      return new CensusResource( $extension );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Extension $extension)
    {
      $extension->residents()->delete();
      $extension->delete();
      return response()->json(['data'=>'Extension deleted successfuly']);
    }

    public function _list(){
      $extensions = Extension::unidad( $request->name )->get();
      return ExtensionResource::collection( $extensions );
    }

    public function getImport(Request $request){
      $admins = Admin::all();
      return view('admin.extensions.import', ['admins'=>$admins]);
    }

    public function import(Request $request) {
      $request->validate([
        'batch' => 'required|mimes:xls,xlsx|max:5000'
      ]);
      
      try{
        $path = $request->batch->store('imports');
        Excel::import(new ExtensionsImport, $path);
        
        $errors = ( session()->get('validation.errors') ) ?: [];
        session()->forget('validation.errors');
        
        return response()->json(['errors'=>$errors]);
      }catch (\Exception $e) {
        return response()->json(["message"=>"Error on import " . $e->getMessage()], 400);
      }
    }
}

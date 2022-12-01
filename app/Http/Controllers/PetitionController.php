<?php

namespace App\Http\Controllers;

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
      $this->middleware('modules:requests');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $petitions = auth()->user()->petitions()->orderBy('created_at', 'DESC')->with('extension')->get();
      if( auth()->user()->admin_id ){
        return view('resident.petitions', compact('petitions'));
      }
      return view('admin.petitions', compact('petitions'));
    }
    
    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
      return view('resident.petitions');
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
        'status'       => 'pending',
        'pictures'     => $this->upload($request, 'pictures'),
        'extension_id' => auth()->user()->id
      ]);
      
      return redirect()->route('petitions.index')->with(['message'=>'Solicitud creada exitosamente']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Petition $petition)
    {
      return view('admin.petition-show', compact('petition'));
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
        'title'        => $request->title       ?: $petition->title,
        'description'  => $request->description ?: $petition->description,
        'phone'        => $request->phone  ?: $petition->phone,
        'email'        => $request->email  ?: $petition->email,
        'status'       => $request->status ?: $petition->status,
        'pictures'     => array_merge($uploadedPictures, $petition->pictures)
      ]);
      
      return redirect()->route('petitions.index')->with(['message'=>'Petición procesada exitosamente']);
    }
    
    public function deletePicture(Request $request, Petition $petition){
      $pictures = collect($petition->pictures)->filter(function($p) use($request){
        return $p['path'] != $request->picture;
      });
      $petition->pictures = $pictures;
      $petition->save();
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
      return response()->json(['data'=>"Petition $petition->id deleted successfuly"]);
    }
}

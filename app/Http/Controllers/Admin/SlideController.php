<?php

namespace App\Http\Controllers\Admin;

use App\Slide;
use App\Admin;
use App\Http\Requests\StoreSlide as StoreSlideRequest;
use App\Http\Resources\Slide as SlideResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

use App\Events\CreatedUpdatedPhone;

class SlideController extends Controller
{
  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function __construct(){
      $this->authorizeResource(Slide::class, 'slide');
  }
  
  public function index()
  {
    // $this->authorize('index', Slide::class);
    return view('slides.vue.index', ['slides'=>Slide::all()]);
  }

  public function list(Request $request)
  {
    return SlideResource::collection( Slide::all() );
  }
  

  /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function create()
  {
    return view('slides.create');
  }

  /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
  public function store(StoreSlideRequest $request)
  {
    $path = "";
    if( $request->hasFile('picture') ){
      $path = $request->file('picture')->store('public/pictures');
      $path = str_replace( 'public/', 'storage/', $path);
    }
    
    $slide = Slide::create([
      'title'     => $request->title,
      'body'      => $request->body,
      'picture'   => $path
    ]);

    return new SlideResource( $slide );
  }

  /**
  * Display the specified resource.
  *
  * @param  \App\Slide  $slide
  * @return \Illuminate\Http\Response
  */
  public function show(Slide $slide)
  {
    return new SlideResource( $slide );
  }

  /**
  * Show the form for editing the specified resource.
  *
  * @param  \App\Slide  $slide
  * @return \Illuminate\Http\Response
  */
  public function edit(Slide $slide)
  {
    return view('slides.edit', ['slide'=>$slide]);
  }

  /**
  * Update the specified resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @param  \App\Slide  $slide
  * @return \Illuminate\Http\Response
  */
  public function update(StoreSlideRequest $request, Slide $slide)
  {
    $path = '';
    if( $request->hasFile('picture') ){
      if($slide->picture){
        $path = str_replace('storage', 'public', $slide->picture);
        if( Storage::exists( $path ) ){
          Storage::delete( $path );
        }
      }
      $path = $request->file('picture')->store('public/pictures');
      $path = str_replace( 'public/', 'storage/', $path);
    }
    
    $slide->update([
      'title'    => $request->title,
      'body'     => $request->body,
      'picture'  => ($path) ?: $slide->picture
    ]);

    return new SlideResource( $slide );
  }

  /**
  * Remove the specified resource from storage.
  *
  * @param  \App\Slide  $slide
  * @return \Illuminate\Http\Response
  */
  public function destroy(Slide $slide)
  {
    $slide->delete();
    return response()->json(['message'=>'Slide deleted successfuly']);
  }
  
}
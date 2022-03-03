<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Novelty as NoveltyResource;
use App\Http\Requests\StoreNovelty;
use App\Novelty;

class NoveltyController extends Controller
{
  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function index()
  {
      $novelties = auth()->user()->novelties()->orderBy('created_at', 'DESC')->get();
      return NoveltyResource::collection( $novelties );
  }

  /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
  public function store(Request $request)
  {
    $json_pictures = [];
    $novelty = Novelty::create([
      'description' => $request->description,
      'porteria_id' => auth()->user()->id ?: $request->porteria_id
    ]);

    for($i = 1; $i < 4; $i++){
      if( $picture = $request->file('picture'.$i) ){
        $path = $picture->store('public/news');
        $json_pictures[] = [
          'path' => $path,
          'url'  => str_replace('public', 'storage', $path)
        ];
      }
    }
    $novelty->pictures = $json_pictures;
    $novelty->save();
    return new NoveltyResource( $novelty );
  }

  /**
  * Display the specified resource.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function show(Novelty $novelty)
  {
    return new NoveltyResource( $novelty );
  }

  /**
  * Update the specified resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function update(Request $request, Novelty $novelty)
  {
    $novelty->update([
      'description' => $request->description,
      'porteria_id' => $request->porteria_id,
    ]);

    return new NoveltyResource( $novelty );
  }

  /**
  * Remove the specified resource from storage.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function destroy(Novelty $novelty)
  {
    $novelty->delete();
    return response()->json(['data'=>"Novelty {$novelty->id} deleted successfuly"]);
  }
}

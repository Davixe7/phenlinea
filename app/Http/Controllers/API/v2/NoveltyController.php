<?php

namespace App\Http\Controllers\API\v2;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Novelty as NoveltyResource;
use App\Novelty;
use PhpParser\Node\Expr\Instanceof_;

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
    $novelty = Novelty::create([
      'description' => $request->description,
      'porteria_id' => $request->porteria_id ?: auth()->user()->id
    ]);
    
    foreach( range(1,3) as $index){
        if( $file = $request->file('picture'.$index) ){
            $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . time() . '.' . $file->extension();
            $path     = storage_path( 'app/'.$file->storeAs('novelties/pictures/', $fileName) );
            $novelty->addMedia( $path )->toMediaCollection('pictures');
        }
    }

    if( $files = $request->file('pictures') ){
      foreach( $files as $file ){
        $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . time() . '.' . $file->extension();
        $path     = storage_path( 'app/'.$file->storeAs('novelties/pictures/', $fileName) );
        $novelty->addMedia( $path )->toMediaCollection('pictures');
      }
    }

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
    $data = $request->validate([
      'description' => 'sometimes|string',
      'read_at'     => 'sometimes'
    ]);
    
    $novelty->update(array_merge($data, ['read_at' => $request->read_at ?: $novelty->read_at]));

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
    return response()->json([], 204);
  }
}

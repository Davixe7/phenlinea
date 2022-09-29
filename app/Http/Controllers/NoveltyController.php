<?php

namespace App\Http\Controllers;

use App\Novelty;
use App\Http\Resources\Novelty as NoveltyResource;
use Illuminate\Http\Request;

class NoveltyController extends Controller {

    // public function __construct(){
    //   $this->middleware('modules:news');
    // }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $novelties = auth()->user()->novelties()->orderBy('created_at', 'desc')->get();
      return view('admin.novelties', compact('novelties'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Novelty  $novelty
     * @return \Illuminate\Http\Response
     */
    public function show(Novelty $novelty)
    {
      return view('admin.novelties', compact('novelty'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Novelty  $novelty
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Novelty $novelty)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Novelty  $novelty
     * @return \Illuminate\Http\Response
     */
    public function destroy(Novelty $novelty)
    {
        //
    }
    
    public function markAsRead(Novelty $novelty)
    {
      $novelty->read = 1;
      $novelty->save();
      return response()->json(['data'=>'Novelty marked as read successfuly']);
    }
}

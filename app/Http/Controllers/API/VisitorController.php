<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Visitor as ResourcesVisitor;
use App\Http\Resources\VisitorResource;
use App\Visitor;

class VisitorController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $request->validate(['dni'=>'required']);
    $result = Visitor::whereDni( $request->dni )->firstOrFail();
    return new ResourcesVisitor($result);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $request->validate(['picture'=>'required']);
    $visitor = Visitor::create([
      "type"         => $request->type,
      "company"      => $request->company,
      "arl"          => $request->arl,
      "eps"          => $request->eps,
      "dni"          => $request->dni,
      "name"         => $request->name,
      "phone"        => $request->phone,
      "plate"        => $request->plate,
    ]);

    if( $request->file('picture') ){
      $visitor->addMediaFromRequest('picture')->toMediaCollection('picture');
    }

    return response()->json(['data' => $visitor]);
  }

  /**
   * Show the related resource as json
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function show(Visitor $visitor)
  {
    return new VisitorResource($visitor);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Visitor $visitor)
  {
    $visitor->update([
      "type"         => $request->type,
      "company"      => $request->company,
      "arl"          => $request->arl,
      "eps"          => $request->eps,
      "dni"          => $request->dni,
      "name"         => $request->name,
      "phone"        => $request->phone,
      "plate"        => $request->plate,
    ]);

    return response()->json(['data' => $visitor]);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy(Visitor $visitor)
  {
    $visitor->delete();
    return response()->json(['data' => $visitor]);
  }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Freelancer;
use Illuminate\Http\Request;
use App\Http\Resources\Freelancer as FreelancerResource;
use App\Http\Controllers\Controller;

class FreelancerController extends Controller
{
  public function __construct(){
    $this->authorizeResource(Freelancer::class, 'freelancer');
  }
  
  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  
  public function index()
  {
    $this->authorize('index', Freelancer::class);
    return view('super.freelancers.index');
  }
  
  public function list(Request $request)
  {
    $this->authorize('index', Freelancer::class);
    $freelancers = Freelancer::all();
    return FreelancerResource::collection( $freelancers );
  }
  
  /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function create()
  {
    return view('freelancers.create');
  }
  
  /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
  public function store(Request $request)
  {
    $request->validate([
      'name'     => 'required|between:4,50',
      'email'    => 'required|email',
      'phone'    => 'nullable|digits:10',
      'password' => 'required|between:6,16',
      'rate'     => 'required|between:4,6',
    ]);
    
    $freelancer = Freelancer::create([
      'name'     => $request->name,
      'email'    => $request->email,
      'phone'    => $request->phone ?: null,
      'password' => bcrypt( $request->password ),
      'rate'     => $request->rate,
    ]);
    
    return new FreelancerResource( $freelancer->load('admins') );
  }
  
  /**
  * Display the specified resource.
  *
  * @param  \App\Freelancer  $freelancer
  * @return \Illuminate\Http\Response
  */
  public function show(Freelancer $freelancer)
  {
    return new FreelancerResource( $freelancer );
  }
  
  /**
  * Show the form for editing the specified resource.
  *
  * @param  \App\Freelancer  $freelancer
  * @return \Illuminate\Http\Response
  */
  public function edit(Freelancer $freelancer)
  {
    return view('freelancers.edit', ['freelancer'=>$freelancer]);
  }
  
  /**
  * Update the specified resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @param  \App\Freelancer  $freelancer
  * @return \Illuminate\Http\Response
  */
  public function update(Request $request, Freelancer $freelancer)
  {
    $request->validate([
      'name'     => 'nullable|between:4,50',
      'email'    => 'nullable|email',
      'phone'    => 'nullable|digits:10',
      'password' => 'nullable|between:6,16',
      'rate'     => 'nullable|between:4,6',
    ]);
    
    $freelancer->update([
      'name'     => ($request->name)  ?: $freelancer->name,
      'email'    => ($request->email) ?: $freelancer->email,
      'phone'    => ($request->phone) ?: $freelancer->phone,
      'password' => ($request->password) ? bcrypt( $request->password ) : $freelancer->password,
      'rate'     => ($request->rate) ?: $freelancer->rate
    ]);
    
    return new FreelancerResource( $freelancer );
  }
  
  /**
  * Remove the specified resource from storage.
  *
  * @param  \App\Freelancer  $freelancer
  * @return \Illuminate\Http\Response
  */
  public function destroy(Freelancer $freelancer)
  {
    $freelancer->delete();
    return response()->json(['message'=>'Succesful deletion']);
  }
}

<?php

namespace App\Http\Controllers\API;

use App\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin as AdminResource;

class AdminController extends Controller
{
  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */

  public function __construct(){
      $this->authorizeResource(Admin::class, 'admin');
  }

  public function index()
  {
    $admins = Admin::all();
    return AdminResource::collection( $admins );
  }

  /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
  public function store(Request $request)
  {
    $admin = Admin::create([
      'name'     => $request->name,
      'key'      => mt_rand(100000, 999999),
      'phone'    => $request->phone,
      'nit'      => $request->nit,
      'address'  => $request->address,
      'email'    => $request->email,
      'password' => bcrypt($request->password),
      'key' => $request->key
    ]);
    return new AdminResource( $admin );
  }

  /**
  * Display the specified resource.
  *
  * @param  \App\Admin  $admin
  * @return \Illuminate\Http\Response
  */
  public function show(Admin $admin)
  {
    return new AdminResource( $admin );
  }

  /**
  * Update the specified resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @param  \App\Admin  $admin
  * @return \Illuminate\Http\Response
  */
  public function update(Request $request, Admin $admin)
  {
    $admin->update([
      'name'     => $request->name     ?: $admin->name,
      'phone'    => $request->phone    ?: $admin->phone,
      'nit'      => $request->nit      ?: $admin->nit,
      'address'  => $request->address  ?: $admin->address,
      'email'    => $request->email    ?: $admin->email,
      'password' => $request->password ? bcrypt($request->password) : $admin->password,
    ]);
    return new AdminResource( $admin );
  }

  /**
  * Remove the specified resource from storage.
  *
  * @param  \App\Admin  $admin
  * @return \Illuminate\Http\Response
  */
  public function destroy(Admin $admin)
  {
    $admin->delete();
    return response()->json(['message'=>'admin successfuly deleted']);
  }
}

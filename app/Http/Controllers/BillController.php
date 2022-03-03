<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Bill as BillResource;
use App\Http\Requests\StoreBill;
use App\Bill;

class BillController extends Controller
{
  
  public function __construct(){
    $this->middleware('modules:payment_links');
    $this->authorizeResource(Bill::class, 'bill');
  }
  
  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function index()
  {
    $role = auth()->getDefaultDriver();
    switch ($role) {
      case 'admin':
        return view('admin.bills.index');
        break;
      default:
        return view('resident.bills');
        break;
    }
  }
  
  public function list()
  {
    return BillResource::collection( auth()->user()->bills );
  }
  
  public function create()
  {
    return view('bills.create');
  }
  
  /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
  public function store(Request $request)
  {
    $bill = Bill::create([
      'admin_id'     => auth()->user()->id,
      'title'        => $request->title,
      'url'          => $request->url
    ]);
    
    return new BillResource( $bill );
  }
  
  /**
  * Display the specified resource.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function show(Bill $bill)
  {
    return new BillResource( $bill );
  }
  
  /**
  * Update the specified resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function update(Request $request, Bill $bill)
  {
    $bill->update([
      'title'  => $request->title ?: $bill->title,
      'url'    => $request->url   ?: $bill->url
    ]);
    
    return new BillResource( $bill );
  }
  
  /**
  * Remove the specified resource from storage.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function destroy(Bill $bill)
  {
    $bill->delete();
    return response()->json(['data'=>'Bill ' . $bill->id . ' deleted successfuly']);
  }
}

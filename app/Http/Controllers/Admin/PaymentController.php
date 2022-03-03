<?php

namespace App\Http\Controllers\Admin;

use App\Payment;
use Illuminate\Http\Request;
use App\Http\Resources\Payment as PaymentResource;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function index()
  {
    $payments = auth()->user()->payments->year();
    return PaymentResource::collection( $payments );
  }
  
  /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function create()
  {
    //
  }
  
  /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
  public function store(Request $request)
  {
    
  }
  
  /**
  * Display the specified resource.
  *
  * @param  \App\Payment  $payment
  * @return \Illuminate\Http\Response
  */
  public function show(Payment $payment)
  {
    //
  }
  
  /**
  * Show the form for editing the specified resource.
  *
  * @param  \App\Payment  $payment
  * @return \Illuminate\Http\Response
  */
  public function edit(Payment $payment)
  {
    
  }
  
  /**
  * Update the specified resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @param  \App\Payment  $payment
  * @return \Illuminate\Http\Response
  */
  public function update(Request $request, Payment $payment)
  {
    $payment->update([
      'm1'  => $request->m1,
      'm2'  => $request->m2,
      'm3'  => $request->m3,
      'm4'  => $request->m4,
      'm5'  => $request->m5,
      'm6'  => $request->m6,
      'm7'  => $request->m7,
      'm8'  => $request->m8,
      'm9'  => $request->m9,
      'm10' => $request->m10,
      'm11' => $request->m11,
      'm12' => $request->m12
    ]);
    
    return new PaymentResource( $payment );
  }
  
  /**
  * Remove the specified resource from storage.
  *
  * @param  \App\Payment  $payment
  * @return \Illuminate\Http\Response
  */
  public function destroy(Payment $payment)
  {
    //
  }
}

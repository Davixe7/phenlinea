<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Bill as BillResource;
use App\Http\Requests\StoreBill;
use App\Bill;
use App\Traits\Uploads;

class BillController extends Controller
{
    use Uploads;
    
    public function __construct(){
      $this->authorizeResource(Bill::class, 'bill');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      return BillResource::collection( auth()->user()->bills );
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
        'title'        => $request->title,
        'url'          => $request->url,
        'admin_id'     => $request->user()->id,
        'extension_id' => $request->extension_id,
        'pictures'     => $this->upload($request, 'pictures')
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
        'title'       => $request->title ?: $bill->title,
        'description' => $request->description ?: $bill->description,
        'pictures'    => array_merge( $this->upload($request, 'pictures'), $bill->pictures ?: [] )
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
      return response()->json(['data'=>'Bill' . $bill->id . ' deleted successfuly']);
    }
    
    public function deletePicture(Request $request, Bill $bill){
      $pictures = collect($bill->pictures)->filter(function($p) use($request){
        return $p['path'] != $request->picture;
      });
      $bill->pictures = $pictures;
      $bill->save();
      return new BillResource( $bill );
    }
}

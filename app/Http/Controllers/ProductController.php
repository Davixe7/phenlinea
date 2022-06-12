<?php

namespace App\Http\Controllers;

use App\Product;
use App\Store;
use App\Attachment;
use Illuminate\Http\Request;
use App\Traits\Uploads;
use App\Http\Resources\ProductResource;
use App\Http\Resources\MediaResource;

class ProductController extends Controller
{
    use Uploads;
    
    public function __construct(){
      $this->authorizeResource(Product::class, 'product');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $menu = auth()->user()->menu;
      return view('public.menu', ['menu'=>$menu]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $product = Product::create([
        'name'        => $request->name,
        'description' => $request->description,
        'price'       => $request->price,
        'category_id' => $request->category_id,
        'store_id'    => auth()->user()->id
      ]);
      
      if( $request->hasFile('pictures') ){
          foreach( $request->file('pictures') as $picture ){
              $product->addMedia( $picture )->toMediaCollection('pictures');
          }
      }
      
      return new ProductResource( $product );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
      $product->update([
        'name'        => $request->name,
        'description' => $request->description,
        'price'       => $request->price,
        'category_id' => $request->category_id
      ]);
      
      if( $request->hasFile('pictures') ){
          foreach( $request->file('pictures') as $picture ){
              $product->addMedia( $picture )->toMediaCollection('pictures');
          }
      }
      
      return response()->json(['data'=>$product]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
      $product->delete();
      return response()->json(['data'=>"Producto $product->id eliminado exitosamente"]);
    }
    
    public function deletePicture(Request $request, Product $product){
        $picture = $product->getMedia('pictures')->filter(function($f){
            return $f->original_url == request()->picture;
        })->shift();
        
        if( $picture ){
            $picture->delete();
        }
        
        return new MediaResource($picture);
    }
}

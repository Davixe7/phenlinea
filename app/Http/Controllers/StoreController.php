<?php

namespace App\Http\Controllers;

use App\Store;
use Illuminate\Http\Request;
use App\Traits\Uploads;
use App\Http\Resources\Store as StoreResource;
use App\Http\Resources\MediaResource;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Hash;

class StoreController extends Controller
{
  use Uploads;
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $stores = StoreResource::collection(Store::all());
    return view('public.stores', ['stores' => $stores]);
  }

  public function show(Store $store)
  {
    $menu  = ProductResource::collection($store->menu);
    $store = new StoreResource($store->load('menu'));
    return view('public.menu', ['store' => $store, 'menu' => $menu]);
  }

  public function update(Store $store, Request $request)
  {

    if ($request->hasFile('logo')) {
      $store->addMedia($request->file('logo'))->toMediaCollection('logo');
    }

    if ($request->hasFile('pictures')) {
      foreach ($request->file('pictures') as $picture) {
        $store->addMedia($picture)->toMediaCollection('pictures');
      }
    }

    $store->update([
      'nit'       => $request->nit ?: $store->nit,
      'name'      => $request->name ?: $store->name,
      'description' => $request->description ?: $store->description,
      'phone_1'   => $request->phone_1 ?: $store->phone_1,
      'email'     => $request->email ?:  $store->email,

      'lat'       => $request->lat && $request->lat != 'null' ? $request->lat : $store->lat,
      'lng'       => $request->lng && $request->lng != 'null' ? $request->lng : $store->lng,
      'address'   => $request->address ?: $store->address,

      'category'  => $request->category ?: $store->category,
      'schedule'  => json_decode($request->schedule) ?: $store->schedule
    ]);

    return new StoreResource($store);
  }

  public function deletepicture(Request $request, Store $store)
  {
    $pictures = $store->getMedia('pictures');
    $picture =
      $pictures->filter(function ($p) use ($request) {
        return $p->original_url == $request->picture;
      })->shift();

    if ($picture) {
      $picture->delete();
    }

    return response()->json(['data' => $request->picture, 'message' => 'Picture deleted successfully']);
  }

  public function resetPassword(Store $store, Request $request)
  {
    $request->validate([
      'old_password'     => [
        function ($field, $value, $fail) {
          if (!Hash::check($value, auth()->user()->password)) {
            $fail('La contraseña actual es incorrecta');
          }
        }
      ],
      'password'              => 'min:6|max:16|confirmed',
      'password_confirmation' => 'same:password',
    ]);
    $store->update(['password' => bcrypt($request->password), '_password' => $request->password]);
    return response()->json(['data' => $store]);
  }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\Store as StoreResource;
use App\Http\Resources\User as UserResource;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
      $user = auth()->user();
      $driver = auth()->getDefaultDriver();
      
      switch ($driver) {
        case 'extension':
          return view('resident.home', ['posts'=>auth()->user()->posts()->whereType('post')->get()]);
          break;
        case 'freelancer':
          return view('admins.referrals');
          break;
        case 'admin':
          $extensions = auth()->user()->extensions()->orderBy('name')->get();
          return view('admin.extensions.index', compact('extensions'));
          break;
        case 'web':
          return view('super.users.index', ['users'=>UserResource::collection( \App\User::all() )]);
          break;
        case 'store':
          $store = new StoreResource($user->load('menu'));
          return view('commerce.profile', ['commerce'=>$store]);
          break;
      }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\Store as StoreResource;

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
      $user   = auth()->user();
      $driver = 'web';
      
      if( $user->admin_id ){
        $driver = 'extension';
      }elseif( $user->nit ){
        $driver = 'admin';
      }
      
      switch ($driver) {
        case 'extension':
          return view('resident.home', ['posts'=>auth()->user()->posts()->whereType('post')->get()]);
          break;
        case 'admin':
          return view('admin.census.index');
          break;
        case 'web':
          return view('super.users.index');
          break;
      }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
          return view('resident.home');
          break;
        case 'freelancer':
          return view('admins.referrals');
          break;
        case 'admin':
          return view('admin.census.index');
          break;
        case 'web':
          return view('super.users.index');
          break;
        case 'store':
          return view('commerce.profile', ['commerce'=>$user->load('menu.pictures')]);
          break;
      }
    }
}

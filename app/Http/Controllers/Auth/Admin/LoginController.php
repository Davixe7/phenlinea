<?php

namespace App\Http\Controllers\Auth\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */

     protected $redirectTo = '/extensions';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
      config(['session.lifetime' => 30]);
      $this->middleware('guest:admin')->except('logout');
    }

    public function showLoginForm(){
      if (auth()->check()) {
        return redirect('/census');
      }

      return view('login');
    }

    protected function guard(){
      return auth()->guard('admin');
    }

    // protected function redirectTo(){
    //   return redirect()->route('admins.index');
    // }
}

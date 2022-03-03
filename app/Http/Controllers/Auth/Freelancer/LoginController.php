<?php

namespace App\Http\Controllers\Auth\Freelancer;

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

     protected $redirectTo = '/admins';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
      $this->middleware('guest:freelancer')->except('logout');
    }

    public function showLoginForm(){
      if (auth()->check()) {
        return redirect('/admins');
      }

      return view('freelancer.login');
    }

    protected function guard(){
      return auth()->guard('freelancer');
    }

    // protected function redirectTo(){
    //   return redirect()->route('admins.index');
    // }
}

<?php

namespace App\Http\Controllers\Auth\Porteria;

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
      $this->middleware('guest:porteria')->except('logout');
    }

    public function showLoginForm(){
      if (auth()->check()) {
        return redirect('/extensions');
      }

      return view('porterias.login');
    }

    protected function guard(){
      return auth()->guard('porteria');
    }

    // protected function redirectTo(){
    //   return redirect()->route('admins.index');
    // }
}

<?php

namespace App\Http\Controllers\Auth\Store;

use App\Http\Controllers\Controller;
use App\Http\Resources\Store as StoreResource;

use Illuminate\Foundation\Auth\AuthenticatesUsers;

use App\Store;

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

     protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
      config(['session.lifetime' => 30]);
      $this->middleware('guest:store')->except('logout');
    }

    public function showLoginForm(){
      return view('auth.stores.login');
    }
    
    public function username(){
      return '_email';
    }
    
    protected function guard(){
      return auth()->guard('store');
    }
}

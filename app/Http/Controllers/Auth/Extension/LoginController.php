<?php

namespace App\Http\Controllers\Auth\Extension;

use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use App\Http\Resources\UnitsLogin as UnitsResource;
use App\Http\Resources\Extension as ExtensionResource;
use Illuminate\Http\Request;

use Illuminate\Foundation\Auth\AuthenticatesUsers;

use App\Admin;
use App\Extension;
use App\Resident;

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
      $this->middleware('guest:extension')->except('logout');
    }

    public function showLoginForm(){
      $admins = UnitsResource::collection( \App\Admin::all() );
      return view('resident.auth.login', ['admins'=>$admins]);
    }
    
    public function extensionslist(Admin $admin){
      return ExtensionResource::collection( Extension::where('admin_id', $admin->id)->orderBy('name')->get() );
    }
    
    public function adminslist(){
      //return UnitsResource::collection( Admin::whereNotIn('id', [1,3,7,13])->get() );
      return UnitsResource::collection( Admin::all() );
    }
    
    public function username(){
      return '_email';
    }
    
    protected function guard(){
      return auth()->guard('extension');
    }

    protected function sendFailedLoginResponse(Request $request)
    {
      throw ValidationException::withMessages([
        'extension' => 'Número de apartamento o contraseña incorrectos',
        'password'  => 'Número de apartamento o contraseña incorrectos',
      ]);
    }
    
    public function login(Request $request)
    {
        $request->validate([
          'admin_id'  => 'required',
          'extension' => 'required',
          'password'  => 'required',
        ]);
        
        $extension = \App\Extension::whereName( $request->extension )->where('admin_id', $request->admin_id)->first();

        if( $extension ){
          $resident  = $extension->residents()->where('dni', $request->password)->first();
          if( $resident ){
            auth()->guard('extension')->login( $extension );
            return $this->sendLoginResponse($request);
          }
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }
}

<?php

namespace App\Http\Controllers\Auth\Admin;

use App\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:admins'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],

            'contact_email' => 'required|email',
            'address'       => 'required',
            'nit'           => 'required|unique:admins',
            'email'         => 'required|unique:admins',
            'phone'         => 'required|numeric|digits:10',
            'phone_2'       => 'nullable|numeric|digits_between:10,10',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return Admin::create([
          'contact_email' => $data['contact_email'],
          'nit'      => $data['nit'],
          'name'     => $data['name'],
          'phone'    => $data['phone'],
          'phone_2'  => $data['phone_2'],
          'address'  => $data['address'],

          'email'      => $data['email'],
          'password'   => bcrypt( $data['password'] ),
        ]);
    }

    protected function guard()
    {
      return auth()->guard('admin');
    }

    public function showRegistrationForm()
    {
      return view('auth.signup');
    }
}

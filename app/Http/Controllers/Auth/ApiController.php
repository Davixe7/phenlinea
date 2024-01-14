<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\DeviceToken;
use App\Extension;

class ApiController extends Controller
{
  public function login(Request $request){

    $credentials = [
      'email'    => $request->email,
      'password' => $request->password
    ];

    if( Auth::attempt( $credentials ) ){
      $token = Str::random(60);
      $token = bcrypt( $token );

      $user = Auth::user();
      $user->forceFill(['api_token'=>$token])->save();

      return response()->json(['user'=>$user], 200);
    }

    return response()->json(['error'=>'Unauthorized'], 401);
  }

  public function adminLogin(Request $request){

    $credentials = [
      'email'    => $request->email,
      'password' => $request->password
    ];

    if( Auth::guard('admin')->attempt( $credentials ) ){
      $token = Str::random(60);
      $token = bcrypt( $token );

      $user = Auth::guard('admin')->user();
      $user->forceFill(['api_token'=>$token])->save();

      return response()->json(['user'=>$user], 200);
    }

    return response()->json(['error'=>'Unauthorized'], 401);
  }

  public function porteriaLogin(Request $request){
    $request->validate([
      'email' => 'required|email',
      'password' => 'required'
    ]);

    $credentials = [
      'email'    => $request->email,
      'password' => $request->password
    ];

    if( ! $token = Auth::guard('api-porteria')->attempt( $credentials ) ){
        return response()->json(['error'=>'Unauthorized'], 401);  
    }
    
    $user = Auth::guard('api-porteria')->user();
    $user->forceFill(['api_token'=>$token]);
    
    return response()->json([
        'user'       => $user,
        'token_type' => 'bearer',
        'expires_in' => 10000
    ]);
    
  }

  public function extensionLogin(Request $request){

    $credentials = [
      '_email'   => $request->email,
      'password' => $request->password
    ];
    
    $extension = Extension::where('email', $request->email)->firstOrFail();
    $resident  = $extension->residents()->where('dni', $request->password)->first();
    
    if( $resident && $user = $resident->extension ){
      $token = Str::random(60);
      $token = bcrypt( $token );

      $user->forceFill(['api_token'=>$token])->save();
      
      if( $request->device_token ){
          $this->registerToken($user->id, $request->device_token);
      }

      return response()->json(['user'=>$user], 200);
    }

    return response()->json(['error'=>'Unauthorized'], 401);
  }

  public function storeLogin(Request $request){

    $credentials = [
      '_email'   => $request->email,
      'password' => $request->password
    ];

    if( Auth::guard('store')->attempt( $credentials ) ){
      $token = Str::random(60);
      $token = bcrypt( $token );

      $user = Auth::guard('store')->user();
      $user->forceFill(['api_token'=>$token])->save();

      return response()->json(['user'=>$user], 200);
    }

    return response()->json(['error'=>'Unauthorized'], 401);
  }
  
  public function registerToken($extension_id, $token){
      if( !DeviceToken::where('extension_id', $extension_id)->where('token_id', $token)->first() ){
          DeviceToken::create([
              'extension_id' => $extension_id,
              'token_id'     => $token
          ]);
      }
  }
}

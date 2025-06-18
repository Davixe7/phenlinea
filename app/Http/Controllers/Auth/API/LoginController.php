<?php

namespace App\Http\Controllers\Auth\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\DeviceToken;

class LoginController extends Controller
{
  public function login(Request $request)
  {

    $credentials = [
      'email'    => $request->email,
      'password' => $request->password
    ];

    if (Auth::guard('web')->attempt($credentials)) {
      $token = Str::random(60);
      $token = bcrypt($token);

      $user = Auth::user();
      $user->forceFill(['api_token' => $token])->save();

      return response()->json(['user' => $user], 200);
    }

    return response()->json(['error' => 'Unauthorized'], 401);
  }

  public function adminLogin(Request $request)
  {

    $credentials = [
      'email'    => $request->email,
      'password' => $request->password
    ];

    if (Auth::guard('admin')->attempt($credentials)) {
      $token = Str::random(60);
      $token = bcrypt($token);

      $user = Auth::guard('admin')->user();
      $user->forceFill(['api_token' => $token])->save();

      return response()->json(['user' => $user], 200);
    }

    return response()->json(['error' => 'Unauthorized'], 401);
  }

  public function porteriaLogin(Request $request)
  {
    $credentials = $request->validate(['email' => 'required','password' => 'required']);

    if (! $token = Auth::guard('api-porteria')->attempt($credentials)) {
      return response()->json(['error' => 'Unauthorized'], 401);
    }

    $user = Auth::guard('api-porteria')->user();
    $user->forceFill(['api_token' => $token]);

    return response()->json([
      'user'       => $user,
      'token_type' => 'bearer',
      'expires_in' => 10000
    ]);
  }

  public function registerToken($extension_id, $token)
  {
    if (!DeviceToken::where('extension_id', $extension_id)->where('token_id', $token)->first()) {
      DeviceToken::create([
        'extension_id' => $extension_id,
        'token_id'     => $token
      ]);
    }
  }
}

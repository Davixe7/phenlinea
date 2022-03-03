<?php

namespace App\Http\Controllers;
use Illuminate\Validation\ValidationException;

use Illuminate\Http\Request;

class PhoneVerificationController extends Controller
{
  public function verifyPhone(Request $request){
    if( auth()->user()->phone_verification != $request->verification_code ){
      throw ValidationException::withMessages(['verification_code' => 'Código de verificación invalido']);
    }
    
    auth()->user()->update(['phone_verification' => null]);
    return redirect()->route('home')->with([
      'message' => 'Número de teléfono verificado exitosamente',
      'message_type' => 'success'
    ]);
  }

  public function resendVerificationCode(){
    return 'ok';
  }
}

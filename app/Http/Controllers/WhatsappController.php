<?php

namespace App\Http\Controllers;

use App\Admin;
use App\BatchMessage;
use App\Extension;
use App\Traits\Whatsapp;
use App\WhatsappClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class WhatsappController extends Controller
{

  /* public function getInstanceId(){
    $instance_id = auth()->user()->whatsapp_instance_id ?: $this->whatsapp->getInstanceId();
    $this->whatsapp->setWebhook($instance_id, route('whatsapp.hook'));
    return response()->json(['data'=>$instance_id]);
  }

  public function getQrCode(Request $request){
    $request->validate(['instance_id'=>'required']);
    $instance_id = $request->instance_id;
    $base64      = $this->whatsapp->getQrCode($instance_id);
    return response()->json(['data'=>$base64]);
  }

  public function authenticate(Request $request){
    auth()->user()->update(['whatsapp_instance_id' => $request->instance_id]);
    $message = auth()->user()->batch_messages()->whereStatus('pending')->latest()->first();
    if( $message ){
      $message->update(['status'=>'ready']);
    }
    return response()->json(['data'=>'success']);
  }

  public function logout(Request $request){
    $this->whatsapp->logout($request->instance_id);
  } */
  
}

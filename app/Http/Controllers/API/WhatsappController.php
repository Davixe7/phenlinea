<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Traits\Whatsapp;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Storage;
use App\Notifications\DeliveryWANotification;

class WhatsappController extends Controller
{ 
  public function sendDelivery(Request $request, $name = null)
  {
    $request->validate(['name:required']);
    $extension = auth()->user()->extensions()->whereName($request->name)->firstOrFail();
    $media_url = null;
    
    if ( $request->hasFile('media') ) {
      try {
        $extension->addMedia( $request->file('media') )->toMediaCollection('deliveries');
        $media_url = $extension->getMedia('deliveries')->last()->getUrl(); 
      } catch(Exception $e){
        Storage::append('deliveries.log', now() . ' ' . $e->getMessage() . ' ' . json_encode($request->all()) );
      }
    }

    if( count($extension->valid_whatsapp_phone_numbers) >= 1 ){
      $extension->notify( new DeliveryWANotification($extension, $media_url) );
      sleep(2);
    }

    // Mobile App Expects
    // $response = $data['media_url'] ? 'Message sent successfully' : ['message' => 'Message sent successfully'];
    // return response()->json(['data' => $response]);
    return response()->json(['data' => $media_url
                                       ? 'Message sent successfully'
                                       : ['message' => 'Message sent successfully']]);
  }
}


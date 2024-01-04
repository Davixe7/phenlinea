<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Traits\Whatsapp;
use Illuminate\Http\Request;
use App\WhatsappClient;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Storage;

class WhatsappController extends Controller
{ 
  public function sendDelivery(Request $request, $name = null)
  {
    $request->validate(['name:required']);
    $extension = auth()->user()->extensions()->whereName($request->name)->firstOrFail();
    $media_url = null;
    
    if ( $file = $request->file('media') ) {
      try {
        $extension->addMedia( $file )->toMediaCollection('deliveries');
        $media_url = $extension->getMedia('deliveries')->last()->getUrl(); 
      }catch(Exception $e){
        Storage::append('deliveries.log', $e->getMessage());
      }
    }

    $whatsapp  = new Whatsapp();

    $options = [
      'number'    => '',
      'message'   => view('messages.delivery', compact('extension'))->render(),
      'media_url' => $media_url ?: null,
      'group_id'  => null
    ];

    foreach( $extension->valid_whatsapp_phone_numbers as $phone ){
      $options['number'] = '57' . $phone;
      $whatsapp->send($options);
      sleep(1);
    }

    // Mobile App Expects
    // $response = $data['media_url'] ? 'Message sent successfully' : ['message' => 'Message sent successfully'];
    // return response()->json(['data' => $response]);
    return response()->json(['data' => $media_url
                                       ? 'Message sent successfully'
                                       : ['message' => 'Message sent successfully']]);
  }
}


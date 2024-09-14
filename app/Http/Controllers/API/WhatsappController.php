<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Traits\Whatsapp;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Storage;

class WhatsappController extends Controller
{ 
  public function sendDelivery(Request $request, $name = null)
  {
    $request->validate(['name:required']);
    $extension = auth()->user()->extensions()->whereName($request->name)->firstOrFail();

    $whatsapp  = new Whatsapp();
    $media_url = null;
    
    if ( $request->hasFile('media') ) {
      try {
        $extension->addMedia( $request->file('media') )->toMediaCollection('deliveries');
        $media_url = $extension->getMedia('deliveries')->last()->getUrl(); 
      } catch(Exception $e){
        Storage::append('deliveries.log', now() . ' ' . $e->getMessage() . ' ' . json_encode($request->all()) );
      }
    }

    $options = [
      'number'    => '',
      'message'   => view('messages.delivery', compact('extension'))->render(),
      'media_url' => $media_url ?: null,
      'group_id'  => null
    ];

    if( $extension->admin_id == 1 ){
      $options['number'] = '584147912134';
      $whatsapp->send($options);
      return response()->json(['data' => $media_url
                                       ? 'Message sent successfully'
                                       : ['message' => 'Message sent successfully']]);
    }

    foreach( $extension->valid_whatsapp_phone_numbers as $phone ){
      $options['number'] = '57' . $phone;
      $whatsapp->send($options);
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


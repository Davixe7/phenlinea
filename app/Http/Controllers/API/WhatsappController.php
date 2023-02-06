<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Extension;
use App\Jobs\ProcessDeliveries;
use GuzzleHttp\Client;


class WhatsappController
{

  public function sendDelivery($name = null, Request $request)
  {
    $request->validate(['name:required']);
    $extension = auth()->user()->extensions()->whereName($request->name)->firstOrFail();

    $media = null;

    if ($request->hasFile('media')) {
      $file = $request->file('media');
      $media = $extension->addMedia($file)->toMediaCollection('deliveries');
    }

    $client = new Client([
      'base_uri' => 'http://asistbot.com/api/',
      'verify' => false
    ]);

    $data = [
      'extension' => $extension->name,
      'admin'     => $extension->admin->name,
      'media_url' => $media ? $media->original_url : ''
    ];

    $phonesCount = 2;
    $validPhones = [];

    if( $extension->name == '1000' ){
      $data['phone'] = '584147912134';
      $client->post('http://161.35.60.29/api/hello', ['query' => $data]);
      return response()->json(['data' => 'Message sent successfully']);
    }

    for($i = 0; $i < $phonesCount; $i++){
      $index = $i + 1;
      $phone_first_number = $extension["phone_{$index}"] ? $extension["phone_{$index}"][0] : null;
      if($phone_first_number && ($phone_first_number != '3')){ continue; }
      $validPhones[] = '57' . $extension["phone_{$index}"];
    }

    foreach( $validPhones as $phone ){
      $data['phone'] = $phone;
      $client->post('http://161.35.60.29/api/hello', ['query' => $data]);
    }
    
    if( $data['media_url'] ){
        return response()->json(['data' => 'Message sent successfully']);
    }

    return response()->json(['data' => ['message' => 'Message sent successfully']]);
  }
}


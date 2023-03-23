<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Extension;
use App\Jobs\ProcessDeliveries;
use GuzzleHttp\Client;


class WhatsappController
{
  protected $api = null;
  
  
  public function __construct(){
      $this->api = $client = new Client([
        'base_uri' => 'http://asistbot.com/api/',
        'verify'   => false
      ]);
  }
  
  public function getMessage($admin, $extension){
      $message = "📦Encomienda Recibida.📦" . "\n\n";
      $message .= "Unidad: *{$admin}*" . "\n";
      $message .= "Apartamento: *{$extension}*" . "\n\n";
      $message .= "Favor pasar a recogerla. 👍" . "\n\n";
      $message .= "Servicio prestado por Phenlinea.com" . "\n";
      return $message;
  }

  public function sendDelivery($name = null, Request $request)
  {
    $request->validate(['name:required']);
    $extension = auth()->user()->extensions()->whereName($request->name)->firstOrFail();
    
    $data = [
      'access_token' => '3f8b18194536bdafa301c662dc9caa4c',
      'instance_id'  => '63E4086C78BC6',
      'type'         => 'text',
      'message'      => $this->getMessage( $extension->name, $extension->admin->name )
    ];
    
    //if( $extension->name == '1000' ){
    //  $data['number'] = '584147912134';
    //  $response = $this->api->post('send.php', ['query' => $data]);
    //  return $response->getBody();
    //}
    
    $media             = null;
    if ($file = $request->file('media')) {
      $media             = $extension->addMedia($file)->toMediaCollection('deliveries');
      $data['type']      = 'media';
      $data['media_url'] = $extension->getFirstMedia('deliveries')->original_url;
    }

    foreach( $extension->valid_whatsapp_phone_numbers as $phone ){
      $data['number'] = '57' . $phone;
      $this->api->post('send.php', ['query' => $data]);
    }

    return response()->json(['data' => ['message' => 'Message sent successfully']]);
  }
}


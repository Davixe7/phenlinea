<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Traits\Whatsapp;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\WhatsappClient;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Storage;

class WhatsappController extends Controller
{ 
  public function getMessage($admin, $extension){
      $message = "📦Encomienda Recibida.📦" . "\n\n";
      $message .= "Unidad: *{$admin}*" . "\n";
      $message .= "Apartamento: *{$extension}*" . "\n\n";
      $message .= "Favor pasar a recogerla. 👍" . "\n\n";
      $message .= "Servicio prestado por Phenlinea.com" . "\n";
      return $message;
  }

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

    $client = WhatsappClient::where('enabled', 1)->first();
    $api    = new Whatsapp();

    $instance_id = $client->delivery_instance_id;
    $admin_name  = auth()->user()->admin ? auth()->user()->admin->name : '';
    $message     = $this->getMessage( $admin_name, $extension->name );
    $media_url   = $media_url ?: null;

    foreach( $extension->valid_whatsapp_phone_numbers as $phone ){
      $number = $extension->id == '13955' ? '584147912134' : '57' . $phone;
      try {
        $api->send(
          $instance_id,
          $number,
          $message,
          $media_url,
          null
        );
        sleep(1);
      }
      catch(GuzzleException $e){
        Storage::append( 'deliveries.log', $e->getMessage());
      }
    }

    // Mobile App Expects
    // $response = $data['media_url'] ? 'Message sent successfully' : ['message' => 'Message sent successfully'];
    // return response()->json(['data' => $response]);
    return response()->json(['data' => $media_url
                                       ? 'Message sent successfully'
                                       : ['message' => 'Message sent successfully']]);
  }
}


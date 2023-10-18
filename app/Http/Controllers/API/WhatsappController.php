<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\WhatsappClient;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Storage;

class WhatsappController extends Controller
{
  protected Client $api;
  protected WhatsappClient $client;
  
  
  public function __construct(){
      $this->client = WhatsappClient::where('enabled', 1)->first();
      $this->api = new Client([
        'base_uri' => $this->client->base_url,
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

  public function sendDelivery(Request $request, $name = null)
  {
    $request->validate(['name:required']);
    $extension = auth()->user()->extensions()->whereName($request->name)->firstOrFail();
    $media_url = null;
    
    if ( $file = $request->file('media') ) {
      $extension->addMedia( $file )->toMediaCollection('deliveries');
      $media_url = $extension->getMedia('deliveries')->last()->getUrl(); 
    }

    $admin_name = auth()->user()->admin ? auth()->user()->admin->name : '';
    
    $data = [
      'access_token' => $this->client->access_token,
      'instance_id'  => $this->client->delivery_instance_id,
      'message'      => $this->getMessage( $admin_name, $extension->name ),
      'type'         => $media_url ? 'media' : 'text',
      'media_url'    => $media_url ?: null
    ];
    
    // Mobile App Expects
    // $response = $data['media_url'] ? 'Message sent successfully' : ['message' => 'Message sent successfully'];
    // return response()->json(['data' => $response]);

    foreach( $extension->valid_whatsapp_phone_numbers as $phone ){
      $data['number'] = $extension->id == '13955' ? '584147912134' : '57' . $phone;
      try {
        $response = $this->api->get('send', ['query' => $data]);
      }
      catch(GuzzleException $e){
        Storage::append( 'deliveries.log', $response->getBody() . json_encode($data) );
      }
    }

    return response()->json(['data' => $data['media_url']
                                       ? 'Message sent successfully'
                                       : ['message' => 'Message sent successfully']]);
  }
}


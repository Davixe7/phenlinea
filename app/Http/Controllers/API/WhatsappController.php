<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception;
use Illuminate\Support\Facades\Storage;


class WhatsappController extends Controller
{
  protected $api = null;
  
  
  public function __construct(){
      $this->api = new Client([
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

  public function sendDelivery(Request $request, $name = null)
  {
    $request->validate(['name:required']);
    $extension = auth()->user()->extensions()->whereName($request->name)->firstOrFail();

    $media_url = null;
    Storage::append('deliveries.log', auth()->user()->name . json_encode($request->all()));
    if ( $file = $request->file('media') ) {
      Storage::append('deliveries.log', "Has file: true \n --------------------- \n");
      $extension->addMedia( $file )->toMediaCollection('deliveries');
      $media_url = $extension->getMedia('deliveries')->last()->getUrl(); 
    }

    $admin_name = auth()->user()->admin ? auth()->user()->admin->name : '';
    
    $data = [
      'access_token' => '3f8b18194536bdafa301c662dc9caa4c',
      'instance_id'  => '63E4086C78BC6',
      'type'         => 'text',
      'message'      => $this->getMessage( $admin_name, $extension->name ),
      'type'         => $media_url ? 'media' : 'text',
      'media_url'    => $media_url
    ];
    
    if( $extension->name == '1000' ){
      // $data['number'] = '573144379170';
      $data['number'] = '584147912134';
      $response = $this->api->post('send.php', ['query' => $data]);
      $response = $data['media_url']
                ? 'Message sent successfully'
                : ['message' => 'Message sent successfully'];
      return response()->json(['data' => $response]);
      //return $response->getBody();
    }

    foreach( $extension->valid_whatsapp_phone_numbers as $phone ){
      $data['number'] = '57' . $phone;
      $this->api->post('send.php', ['query' => $data]);
    }

    // try {
    //   foreach( $extension->valid_whatsapp_phone_numbers as $phone ){
    //     $data['number'] = '57' . $phone;
    //     $this->api->post('send.php', ['query' => $data]);
    //   }
    // }
    // catch(Exception $e){
    //   abort(504, 'Asistbot tardó demasiado en responder');
    // }

    $response = $data['media_url']
                ? 'Message sent successfully'
                : ['message' => 'Message sent successfully'];


    return response()->json(['data' => $response]);
  }
}


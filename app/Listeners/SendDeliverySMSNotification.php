<?php

namespace App\Listeners;

use App\Events\DeliveryOrderReceived;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendDeliverySMSNotification
{
    public function handle(DeliveryOrderReceived $event)
    {
        $apto   = $event->extension->name;
        $admin  = $event->extension->admin->name;
        $sms = "Encomienda recibida. En porteria acabamos de recibir un paquete para el apto " . $apto . " de " . $admin . " Por favor pasar a recogerlo. https://phenlinea.com";

        $data = [
          "message"      => null,
          "type"         => "text",
          "instance_id"  => '632CE66281C2B',
          "access_token" => '3f8b18194536bdafa301c662dc9caa4c',
        ];
        $data['message'] = $sms;
      
        if( $event->extension->phone_1 ){
            $post['to'][] = "57" . $event->extension->phone_1;
            $data['number'] = "57" . $event->extension->phone_1;
        }
        if( $event->extension->phone_2 ){
            $post['to'][] = "57" . $event->extension->phone_2;
            $data['number'] = "57" . $event->extension->phone_2;
        }

        $client = new Client([
          'base_uri' => 'http://asistbot.com/api/send.php',
          'verify' => false
        ]);
        
        $response = $client->request('POST', '', ['query' => $data]);

        if( $response->getStatusCode() == '200' ){
          Storage::disk('local')->append('whatsapp.log', implode(",", $data));
          return;
        }
        Storage::disk('local')->append('whatsapp.log', "Error");
    }
}

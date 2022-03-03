<?php

namespace App\Listeners;

use App\Events\DeliveryOrderReceived;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class SendDeliverySMSNotification
{
    public function handle(DeliveryOrderReceived $event)
    {
        $apto   = $event->extension->name;
        $admin  = $event->extension->admin->name;
      
        $url = "http://sms.puntodigitalip.com/Api/rest/message";
        $user ="PHENLINEA";
        $password = '1234567';
        
        $sms = "Encomienda recibida. En porteria acabamos de recibir un paquete para el apto " . $apto . " de " . $admin . " Por favor pasar a recogerlo. https://phenlinea.com";
        $post['text'] = $sms;
        $post['from'] = "PHenlinea";
        $post['parts'] = 2;
        $post['to'] = [];
        
        if( $event->extension->phone_1 ){
            $post['to'][] = "57" . $event->extension->phone_1;
        }
        if( $event->extension->phone_2 ){
            $post['to'][] = "57" . $event->extension->phone_2;
        }
        
        $receivers = $post['to'];
        $post = json_encode($post);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_HTTPHEADER,[
            "Accept: application/json",
            "Authorization: Basic ".base64_encode("{$user}:{$password}")
        ]);
        $result = curl_exec ($ch);
        $result = json_decode( $result );
        
        if( is_array( $result ) ){
            $result = $result[0];
        }
        
        if( $result && property_exists($result, 'accepted') && $result->accepted ){
            $receivers = implode(',', $receivers);
            Storage::disk('local')->append('sms_log.txt', "$sms $receivers");
            return;
        }
        $now = now();
        Storage::disk('local')->append('sms_log.txt', "{$now} Error al enviar sms $sms");
    }
}

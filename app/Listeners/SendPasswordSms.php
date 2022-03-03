<?php

namespace App\Listeners;

use App\Events\SendPasswordSms as SendPasswordEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class SendPasswordSms
{
  /**
  * Create the event listener.
  *
  * @return void
  */
  public $numbers;
  
  /**
  * Handle the event.
  *
  * @param  SendPasswordSms  $event
  * @return void
  */
  
  public function handle(SendPasswordEvent $event)
  {
        $url = "http://sms.puntodigitalip.com/Api/rest/message";
        $user ="PHENLINEA";
        $password = '1234567';
        
        $pwd    = $event->extension->_password ?: $event->extension->password;
        $sms = "Estimado usuario, para recuperar la contraseña por favor pongase en contacto con su administración";
        $post['text'] = $sms;
        $post['from'] = "PHenlinea";
        $post['to'] = [ '57' . $event->extension->passwordResetPhone ];
        
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
        
        if( property_exists($result, 'accepted') && $result->accepted ){
            $receivers = implode(',', $receivers);
            Storage::disk('local')->append('sms_log.txt', "$sms $receivers");
            return;
        }
        $now = now();
        Storage::disk('local')->append('sms_log.txt', "{$now} Error al enviar sms $sms");
  }
}

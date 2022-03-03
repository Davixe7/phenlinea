<?php

namespace App\Listeners;

use App\Events\BulkSmsSent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class SendBulkSms
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
  * @param  BulkSmsSent  $event
  * @return void
  */
  
  public function handle(BulkSmsSent $event)
  { 
    $url = "http://sms.puntodigitalip.com/Api/rest/message";
    $user ="PHENLINEA";
    $password = '1234567';
    
    $sms = $event->sms;
    $post['text'] = $sms;
    $post['from'] = "PHenlinea";
    $post['to'] = $event->receiver;
    
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
    $result = is_array( $result ) ? $result[0] : $result;
    
    if( property_exists($result, 'accepted') && $result->accepted ){
        Storage::disk('local')->append('sms_log.txt', $sms . " " . count($event->receiver) );
        return;
    }
    Storage::disk('local')->append('sms_log.txt', "{now()} Error al enviar sms $sms");
  }
}

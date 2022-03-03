<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class SmsChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $url = "http://sms.puntodigitalip.com/Api/rest/message";
        $user = "PHENLINEA";
        $password = '1234567';
        
        $post['text'] = $sms = $notification->toSms($notifiable);
        $post['from'] = "PHenlinea";
        $post['to'] = [ '57' . $notifiable->phone ];
        
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
        
        Storage::disk('local')->append('sms_log.txt', now(). " Error al enviar sms $sms");
    }
}
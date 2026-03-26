<?php

namespace App\Channels;

use Exception;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HablameNotificationChannel {

    public function send($notifiable, Notification $notification){

        $to_number = $notifiable->routeNotificationForMeta($notification);

        if( !$to_number ){
            throw new Exception("The notifiable did not provide a valid sms phone number {$notifiable->id}");
        }

        $api  = Http::baseUrl('https://www.hablame.co/api/sms/v5')->withHeaders([
            'X-Hablame-Key' => env('HABLAME_API_KEY')
        ]);

        $apto  = $notifiable->name;
        $admin = $notifiable->admin->name;

        $data = ['messages'=>[]];
        $text = "📦Encomienda recibida📦 \n";
        $text .= "Apto: {$apto} \n";
        $text .= "Unidad Residencial: {$admin} \n\n";
        $text .= "Su encomienda fue recibida en porteria, por favor pasar a recogerla. 👍 \n\n";
        $text .= "Servicio prestado por phenlinea.com \n";

        $to_number = !is_array($to_number) ? [$to_number] : $to_number;

        foreach( $to_number as $to ){
            $data['messages'][] = compact('to', 'text');
        }
        
        $response = $api->post('send', $data);
        Log::info($response->body());

        return $response;
    }

}
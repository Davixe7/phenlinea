<?php

namespace App\Channels;

use App\Services\MetaNotificationService;
use Exception;
use Illuminate\Notifications\Notification;

class MetaNotificationChannel {

    public function send($notifiable, Notification $notification){
        $to_number = $notifiable->routeNotificationForMeta($notification);

        if( !$to_number ){
            throw new Exception("The notifiable did not provide a valid whatsapp phone number {$notifiable->id}");
        }

        $api  = new MetaNotificationService();
        $data = $notification->toMeta( $notifiable );

        if( is_array($to_number) ){
            foreach( $to_number as $number ){
                $data['to_number'] = $number;
                sleep(3);
                $response = $api->send($data);
            }
        }
        else {
            $data['to_number'] = $to_number;
            $response = $api->send($data);
        }

        return $response;
    }

}
<?php

namespace App\Channels;

use App\Services\MetaNotificationService;
use Exception;
use Illuminate\Notifications\Notification;

class MetaNotificationChannel {

    public function send($notifiable, Notification $notification){
        $to_number = $notifiable->routeNotificationForMeta($notification);

        if( !$to_number ){
            throw new Exception('The notifiable did not provide a valid whatsapp phone number');
        }

        $data = $notification->toMeta( $notifiable );
        $data['to_number'] = $to_number;
        $api = new MetaNotificationService();
        $response = $api->send($data);
        return $response;
    }

}
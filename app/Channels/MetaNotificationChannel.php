<?php

namespace App\Channels;

use App\Services\MetaNotificationService;
use Illuminate\Notifications\Notification;

class MetaNotificationChannel {

    public function send($notifiable, Notification $notification){
        $data = $notification->toMeta( $notifiable );
        $data['to_number'] = $notifiable->routeNotificationForMeta($notification);
        $api = new MetaNotificationService();
        $response = $api->send($data);
        return $response;
    }

}
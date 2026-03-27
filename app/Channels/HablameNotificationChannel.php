<?php

namespace App\Channels;

use Exception;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HablameNotificationChannel
{

    public function send($notifiable, Notification $notification)
    {

        $to_number = $notifiable->routeNotificationForMeta($notification);

        if (!$to_number) {
            throw new Exception("The notifiable did not provide a valid sms phone number {$notifiable->id}");
        }

        $messageData = $notification->toHablame($notifiable);
        $text = view($messageData['template'], $messageData['data'])->render();

        $to_number = !is_array($to_number) ? [$to_number] : $to_number;

        foreach ($to_number as $to) {
            $data['messages'][] = compact('to', 'text');
        }

        $response = Http::withHeaders(['X-Hablame-Key' => env('HABLAME_API_KEY')])
                    ->post('https://www.hablame.co/api/sms/v5/send', $data);
        //Log::info($response->body());

        return $response;
    }
}

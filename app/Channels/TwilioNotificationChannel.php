<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use App\Services\TwilioService;
use Exception;
use Illuminate\Support\Facades\Log;

class TwilioNotificationChannel
{
    protected $twilio;

    public function __construct(TwilioService $twilio)
    {
        $this->twilio = $twilio;
    }

    /**
     * Enviar la notificación dada.
     */
    public function send($notifiable, Notification $notification)
    {
        if (!method_exists($notification, 'toTwilio')) {
            return;
        }

        $data = $notification->toTwilio($notifiable);
        $to = $notifiable->routeNotificationFor('twilio', $notification);

        if (!$to) {
            Log::warning("No se encontró número de teléfono para el destinatario ID: {$notifiable->id}");
            return;
        }

        $to = !is_array($to) ? [$to] : $to;

        try {
            $lastSentMessage = null;
            foreach($to as $number){
                $lastSentMessage = $this->twilio->sendTemplateMessage(
                    $number, 
                    $data['templateId'],
                    $data['variables']
                );
            }
            return $lastSentMessage;
        } catch (Exception $e) {
            Log::error("Error en TwilioChannel: " . $e->getMessage());
        }
    }
}
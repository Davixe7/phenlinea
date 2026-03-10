<?php

namespace App\Notifications;

use App\Channels\MetaNotificationChannel;
use App\Channels\TwilioNotificationChannel;
use App\Visit;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class VisitorCodeWANotification extends Notification
{
    use Queueable;

    protected $visit;
    protected $media_url;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Visit $visit)
    {
        $this->visit = $visit;
        $this->media_url = $visit->getFirstMediaUrl('qrcode');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [TwilioNotificationChannel::class];
    }

    public function toTwilio($notifiable){
        $media    = $notifiable->getFirstMedia('qrcode');
        $mediaUrl = $media->id . "/" . $media->file_name;

        return [
            'templateId'  => 'HX86bc8d50324882b554dd30c761317fcd',
            'variables' => [
                'unidad'   => $notifiable->admin->name,
                'codigo'   => $notifiable->password,
                'mediaUrl' => $mediaUrl
            ]
        ];
    }

    public function toMeta($notifiable){
        $template_name = 'ingreso_autorizado';

        $header = ['type' => 'header', 'parameters' => [['type'  => 'image', 'image' => ['link' => $this->media_url]]]];

        $body = [
            'type' => 'body',
            'parameters' => [
                [
                    'type' => 'text',
                    'parameter_name' => 'unidad',
                    'text' => $notifiable->admin->name,
                ],
                [
                    'type' => 'text',
                    'parameter_name' => 'codigo_ingreso',
                    'text' => $notifiable->password,
                ],
            ]
        ];

        $components = [$header, $body];
    
        return compact('template_name', 'components');
    }
}

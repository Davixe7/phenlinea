<?php

namespace App\Notifications;

use App\Channels\MetaNotificationChannel;
use App\Visit;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
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
        return [MetaNotificationChannel::class];
    }

    public function toMeta($notifiable){
        $template_name = 'visitante_codigo';

        $header = ['type' => 'header', 'parameters' => [['type'  => 'image', 'image' => ['link' => $this->media_url]]]];

        $body = [
            'type' => 'body',
            'parameters' => [
                [
                    'type' => 'text',
                    'parameter_name' => ' unidad ',
                    'text' => $notifiable->admin->name,
                ],
                [
                    'type' => 'text',
                    'parameter_name' => ' pwd ',
                    'text' => $notifiable->password,
                ],
            ]
        ];

        $components = [$header, $body];
    
        return compact('template_name', 'components');
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}

<?php

namespace App\Notifications;

use App\Channels\MetaNotificationChannel;
use App\Extension;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotifiableViaWhatsapp;

class DeliveryWANotification extends Notification
{
    use Queueable;

    protected $extension;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Extension $extension)
    {
        $this->extension = $extension;
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
        return [
            'template_name' => 'recep_paquete',
            'params' => [
                [
                    'type' => 'text',
                    'parameter_name' => 'apto',
                    'text' => $notifiable->name,
                ],
                [
                    'type' => 'text',
                    'parameter_name' => 'unidad',
                    'text' => $notifiable->admin->name,
                ],
            ]
        ];
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

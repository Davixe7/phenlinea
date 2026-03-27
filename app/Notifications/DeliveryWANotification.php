<?php

namespace App\Notifications;

use App\Channels\HablameNotificationChannel;
use App\Extension;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Channels\TwilioNotificationChannel;

class DeliveryWANotification extends Notification
{
    use Queueable;

    protected $extension;
    protected $media_url;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Extension $extension, $media_url = null)
    {
        $this->media_url = $media_url;
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
        //TwilioNotificationChannel::class
        return [HablameNotificationChannel::class];
    }

    public function toTwilio($notifiable)
    {
        $templateId = 'HX50bbf73f5401dbe86c2da2cc20a45b9b';
        $variables = [
            'unidad' => $notifiable->admin->name,
            'apto'   => $notifiable->name,
        ];

        if( $this->media_url ){
            $templateId = 'HX3fa13962a0f60c57c2706cdcda46fb58';
            $variables['mediaUrl'] = str_replace("https://phenlinea.com//storage/", "", $this->media_url);
        }

        return [
            'templateId' => $templateId,
            'variables'  => $variables
        ];
    }

    public function toMeta($notifiable){
        $template_name = $this->media_url ? 'encomienda_multimedia' : 'encomienda_recibida';

        $header = $this->media_url
        ? ['type' => 'header', 'parameters' => [['type'  => 'image', 'image' => ['link' => $this->media_url]]]]
        : null;

        $body = [
            'type' => 'body',
            'parameters' => [
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

        $components = array_filter([$header, $body]);
    
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

    public function toHablame($notifiable) {
        return [
            'template' => 'notifications.delivery',
            'data' => [
                'apto' => $notifiable->name,
                'admin' => $notifiable->admin->name
            ]
        ];
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

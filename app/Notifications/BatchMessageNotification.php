<?php

namespace App\Notifications;

use App\Channels\MetaNotificationChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BatchMessageNotification extends Notification
{
    use Queueable;

    protected $title;
    protected $content;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(String $title, String $content)
    {
        $this->title = $title;
        $this->content = $content;
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
        $from_number_id = '607278609139986';
        $template_name = 'masivos_utilidad';

        $header = null;

        $body = [
            'type' => 'body',
            'parameters' => [
                [
                    'type' => 'text',
                    'parameter_name' => 'nombre1',
                    'text' => $notifiable->admin->name,
                ],
                [
                    'type' => 'text',
                    'parameter_name' => 'titulo2',
                    'text' => $this->title,
                ],
                [
                    'type' => 'text',
                    'parameter_name' => 'mensaje3',
                    'text' => $this->content,
                ],
            ]
        ];

        $components = array_values(array_filter([$header, $body]));
    
        return compact('from_number_id', 'template_name', 'components');
    }
}

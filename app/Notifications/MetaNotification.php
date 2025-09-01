<?php

namespace App\Notifications;

use App\BatchMessage;
use App\Channels\MetaNotificationChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class MetaNotification extends Notification
{
    use Queueable;

    protected $batch;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(BatchMessage $batch)
    {
        $this->batch = $batch;
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
        $template_name = $this->batch->media_url 
        ? $this->batch->template_name . '_media'
        : $this->batch->template_name;

        $header = $this->batch->media_url
        ? ['type' => 'header', 'parameters' => [['type'  => 'document', 'document' => ['link' => $this->batch->media_url]]]]
        : null;

        $body = [
            'type' => 'body',
            'parameters' => array_merge($this->batch->template_params, [
                [
                    'type' => 'text',
                    'parameter_name' => 'numero_apartamento',
                    'text' => $notifiable->name
                ]
            ])
        ];

        $components = array_values(array_filter([$header, $body]));
    
        return compact('from_number_id', 'template_name', 'components');
    }
}

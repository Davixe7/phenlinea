<?php

namespace App\Jobs;

use App\Extension;
use App\Traits\Whatsapp;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class NotifyDeliveryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $extension;
    public $media_url;
    public $whatsapp;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Extension $extension, string $media_url)
    {
        $this->extension = $this->extension;
        $this->media_url = $this->media_url;
        $this->whatsapp  = new Whatsapp();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $options = [
            'number'    => '',
            'message'   => view('messages.delivery', compact('extension'))->render(),
            'media_url' => $this->media_url ?: null,
            'group_id'  => null
        ];

        if ($this->extension->admin_id == 1) {
            $options['number'] = '584147912134';
            $this->whatsapp->send($options);
            return 'Message sent to ' . '584147912134';
        }

        foreach ($this->extension->valid_whatsapp_phone_numbers as $phone) {
            $options['number'] = '57' . $phone;
            $this->whatsapp->send($options);
            sleep(5);
        }
    }

    public function middleware()
    {
        return [new WithoutOverlapping()];
    }
}

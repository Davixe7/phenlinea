<?php

namespace App\Jobs;

use App\BatchMessage;
use App\Traits\Whatsapp;
use App\WhatsappClient;
use Exception;
use Illuminate\Support\Facades\Storage;
class SendBatchMessage
{
    public function __invoke()
    {
        $client   = WhatsappClient::whereEnabled(1)->first();
        $whatsapp = new Whatsapp( $client );

        $batch = BatchMessage::whereStatus('ready')->where('created_at', '<', now()->subMinute())->firstOrFail();
        $instance_id = $batch->admin->whatsapp_instance_id;
        $batch->update(['status'=>'processing']);

        $numbers = explode( ',', $batch->numbers );
        $options = [
        'instance_id' => $instance_id,
        'number'      => '',
        'message'     => $batch->body,
        'media_url'   => $batch->media_url,
        'group_id'    => null
        ];

        foreach( $numbers as $number ){
            try {
                if($number == '4147912134'){
                    $options['number'] = '584147912134';
                    $whatsapp->send($options);
                    sleep(5);
                    continue;
                }
                $options['number'] = '57' . $number;
                $whatsapp->send($options);
                sleep(5);
            } catch (Exception $e) {
                Storage::append('whatsapp.errors', now() . ' ' . $instance_id . ' ' . $e->getMessage());
            }
        }

        $batch->update(['status'=>'sent']);
    }
}

<?php

namespace App\Jobs;

use App\BatchMessage;
use App\Traits\Whatsapp;
use App\WhatsappClient;

class SendBatchMessage
{
    public function __invoke()
    {
        $client   = WhatsappClient::find(1);
        $whatsapp = new Whatsapp( $client );

        $batch = BatchMessage::whereStatus('ready')->firstOrFail();
        $instance_id   = $batch->admin->whatsapp_instance_id;
        $validInstance = $whatsapp->validateInstance($instance_id, $batch->admin->phone);

        $batch->update(['status' => $validInstance ? 'process' : 'pending']);

        $numbers = explode( ',', $batch->numbers );
        $options = [
        'instance_id' => $instance_id,
        'number'      => '',
        'message'     => $batch->body,
        'media_url'   => $batch->media_url,
        'group_id'    => null
        ];

        foreach( $numbers as $number ){
        if($number == '4147912134'){
            $options['number'] = '584147912134';
            $whatsapp->send($options);
            sleep(5);
            continue;
        }
        $options['number'] = '57' . $number;
        $whatsapp->send($options);
        sleep(5);
        }

        $batch->update(['status'=>'sent']);
    }
}
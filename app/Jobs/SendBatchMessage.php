<?php

namespace App\Jobs;

use App\BatchMessage;
use App\Traits\Whatsapp;
use App\WhatsappClient;

class SendBatchMessage
{
    public function __invoke()
    {
        $client   = WhatsappClient::find(2);
        $whatsapp = new Whatsapp( $client );

        $batch = BatchMessage::whereStatus('ready')->firstOrFail();
        //$validInstance = $whatsapp->validateInstance($client->batch_instance_id, $client->batch_instance_phone);
        $validInstance = true;

        $batch->update(['status' => $validInstance ? 'processing' : 'failed']);

        $numbers = explode( ',', $batch->numbers );
        $options = [
        'instance_id' => $client->batch_instance_id,
        'number'      => '',
        'message'     => $batch->body,
        'media_url'   => $batch->media_url,
        'group_id'    => null
        ];

        foreach( $numbers as $number ){
        if($number == '4147912134'){
            $options['number'] = '584147912134';
            $whatsapp->send($options);
            sleep(3);
            continue;
        }
        $options['number'] = '57' . $number;
        $whatsapp->send($options);
        sleep(5);
        }

        $batch->update(['status'=>'sent']);
    }
}

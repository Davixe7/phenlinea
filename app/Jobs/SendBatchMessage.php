<?php

namespace App\Jobs;

use App\BatchMessage;
use App\Traits\Whatsapp;
use App\WhatsappClient;
use Exception;
use Illuminate\Support\Facades\Storage;
use App\Notifications\BatchMessageNotification;

class SendBatchMessage
{
    public function __invoke()
    {
        $batch = BatchMessage::whereStatus('ready')->where('created_at', '<', now()->subMinute())->firstOrFail();
        $batch->update(['status'=>'processing']);
        $extensions = $batch->receivers()->get(['id', 'name', 'admin_id', 'phone_1', 'phone_2', 'phone_3', 'phone_4']);

        foreach( $extensions as $extension){
            try {
                $extension->notify( new BatchMessageNotification($batch->title, $batch->body) );
                sleep(5);
            } catch (Exception $e) {
                Log::error( $e );
            }
        }

        $batch->update(['status'=>'sent']);
    }
}

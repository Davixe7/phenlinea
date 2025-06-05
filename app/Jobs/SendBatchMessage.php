<?php

namespace App\Jobs;

use App\BatchMessage;
use Exception;
use App\Notifications\BatchMessageNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SendBatchMessage
{
    public function __invoke()
    {
        $batch = BatchMessage::whereStatus('ready')->where('created_at', '<', now()->subMinute())->firstOrFail();
        $batch->update(['status'=>'processing']);
        $extensions = $batch->receivers;

        foreach( $extensions as $extension){
            try {
                Log::info('Enviando mensaje masivo');
                $extension->notify( new BatchMessageNotification($batch->title, $batch->body) );
                sleep(5);
            } catch (Exception $e) {
                Storage::append('meta.log', $e->getMessage());
            }
        }

        $batch->update(['status'=>'sent']);
    }
}

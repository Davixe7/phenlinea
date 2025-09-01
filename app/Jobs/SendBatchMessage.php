<?php

namespace App\Jobs;

use App\BatchMessage;
use Exception;
use App\Notifications\BatchMessageNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Notifications\MetaNotification;

class SendBatchMessage
{
    public function __invoke()
    {
        Log::info('Buscando mensaje masivo');
        $batch = BatchMessage::whereStatus('ready')->where('created_at', '<', now()->subMinute())->firstOrFail();
        Log::info('Mensaje masivo ' . $batch->title);
        Log::info('Receivers: ' . $batch->receivers->count());
        
        $batch->update(['status'=>'processing']);
        
        $extensions = $batch->receivers;

        foreach( $extensions as $extension){
            try {
                Log::info('Enviando mensaje masivo');
                $extension->notify( new MetaNotification($batch) );
                sleep(5);
            } catch (Exception $e) {
                Storage::append('meta.log', $e->getMessage());
            }
        }

        $batch->update(['status'=>'sent']);
    }
}

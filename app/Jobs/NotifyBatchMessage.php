<?php

namespace App\Jobs;

use App\BatchMessage;
use App\Notifications\BatchMessageNotification;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NotifyBatchMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $batch = BatchMessage::whereStatus('ready')->where('created_at', '<', now()->subMinute())->firstOrFail();
        $batch->update(['status'=>'processing']);
        $numbers = explode( ',', $batch->numbers );

        foreach( $numbers as $number ){
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

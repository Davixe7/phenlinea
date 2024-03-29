<?php

namespace App\Console;

use App\BatchMessage;
use App\Traits\Whatsapp;
use App\WhatsappClient;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function(){
          $whatsapp = new Whatsapp( WhatsappClient::findOrFail(1) );

          $batch = BatchMessage::whereStatus('ready')->firstOrFail();
          $ready = $batch->admin->hasValidInstance();

          $batch->update(['status' => $ready ? 'processing' : 'failed' ]);

          $numbers = explode( ',', $batch->numbers );
          $options = [
            'instance_id' => $batch->admin->whatsapp_instance_id,
            'number'      => '',
            'message'     => $batch->body,
            'media_url'   => $batch->media_url,
            'group_id'    => null
          ];

          foreach( $numbers as $number ){
            $options['number'] = '57' . $number;
            $whatsapp->send($options);
            sleep(1);
          }
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

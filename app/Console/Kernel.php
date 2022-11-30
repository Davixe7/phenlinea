<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;
use DB;
use App\WhatsappMessagesBatch;

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
            
            $batch = WhatsappMessagesBatch::where('status', 'pending')->orderBy('created_at', 'ASC')->first();
            if( !$batch ){ return 1; }
            
            $batch->update(['status'=>'taken']);
            
            $client  = new Client([
                'base_uri' => 'https://asistbot.com/api/',
                'verify'   => false
            ]);
            
            $numbers = explode(',', $batch->receivers_numbers );
            
            $message = "*Unidad: {$batch->admin->name}* \n";
            $message = $message . "\n $batch->message \n";
            $message = $message . "\n Servicio prestado por PHenlinea.com";
            
            $data = [
              "access_token" => '3f8b18194536bdafa301c662dc9caa4c',
              "instance_id"  => $batch->admin->whatsapp_instance_id,
              "type"         => "text",
              "message"      => $message
            ];
            
            if( $media_url = $batch->getFirstMediaUrl('attachment') ){
                $data['type'] = 'media';
                $data['media_url']  = $media_url;
                $data['filename']   = $batch->getFirstMedia('attachment')->file_name;
            }
            
            foreach( $numbers as $number ){
                $data['number'] = '57' . $number;
                $client->post('send.php', ['query' => $data]);
                Storage::append('whatsapp_log.txt', now() . " {$batch->admin->name} - {$batch->admin->whatsapp_instance_id} - {$number} - { $batch->message }");
                sleep(3);
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

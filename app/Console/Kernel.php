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

            $admins = DB::table('admins')->whereNotNull('whatsapp_instance_id')->get();

            $asistbot = new Client(['base_uri'=>'http://asistbot.com/api/', 'verify'=>false]);

            foreach( $admins as $admin ){
              $response = $asistbot->post('resetinstance.php', ['query'=>[
                'access_token' => env('ASISTBOT_ACCESS_TOKEN'),
                'instance_id'  => $admin->whatsapp_instance_id
              ]]);

              $body = json_decode($response->getBody());
              Storage::append('asistbot_logout.log', "$body->status $body->message");
              sleep(3);
            }

            DB::table('admins')->update([
                'whatsapp_instance_id' => null,
                'whatsapp_status'      => 'offline',
            ]);
            
        })->dailyAt('11:59');
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

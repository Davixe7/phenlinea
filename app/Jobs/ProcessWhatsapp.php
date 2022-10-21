<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Batchable;

use App\Extension;
use App\Queue\Middleware\RateLimited;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;

class ProcessWhatsapp implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5;
    // public $maxExceptions = 2;
    // public $backoff = 3;
    
    protected $number;
    protected $message;

    public function getJobGroup(){
      return 'whatsapp:'.auth()->id();
    }
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($number, $message)
    {
      $this->number    = $number;
      $this->message   = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // $data = [
        //   "instance_id"  => auth()->user()->whatsapp_instance_id,
        //   "access_token" => '3f8b18194536bdafa301c662dc9caa4c',
        //   "type"         => "text",
        //   "message"      => $this->message,
        //   "number"       => $this->number
        // ];

        // $client = new Client([
        //   'base_uri' => 'http://asistbot.com/api/',
        //   'verify' => false
        // ]);
        
        // $response = $client->post('send.php', ['query' => $data]);

        Storage::disk('local')->append('queue.log', auth()->id() . ' ' . $this->number . ' ' . now());
        return;

        // if( $response->getStatusCode() == '200' ){
        //   Storage::disk('local')->append('whatsapp.log', now() . " Message sent " . $data['number'] . "\n");
        //   return;
        // }
        // Storage::disk('local')->append('whatsapp.log', "Error");
    }
    
    /**
     * Get the middleware the job should pass through.
     *
     * @return array
     *
    **/
    // public function middleware()
    // {
    //   return [new RateLimited()];
    // }
    
}

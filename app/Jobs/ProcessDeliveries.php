<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Extension;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\Middleware\WithoutOverlapping;

class ProcessDeliveries implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $maxExceptions = 2;
    public $backoff = 3;
    
    protected $extension;
    protected $number;
    protected $media;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($extension, $number, $media = null)
    {
        $this->extension = $extension;
        $this->number    = $number;
        $this->media     = $media;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $apto = $this->extension->name;
        $admin = $this->extension->admin->name;
        $sms = "📦*Encomienda Recibida*.📦" . "\n\n";
        $sms .= "Unidad: *{$admin}*" . "\n";
        $sms .= "Apartamento: *{$apto}*" . "\n\n";
        $sms .= "Favor pasar a recogerla. 👍" . "\n\n";
        $sms .= "Servicio prestado por Phenlinea.com";
        
        $data = [
        "instance_id" => '6336107796D94',
        "access_token" => '3f8b18194536bdafa301c662dc9caa4c',
        "type" => "text",
        "message" => $sms,
        "number" => $this->number
        ];
        
        if( $this->media ){
            $data['type'] = 'media';
            $data['media_url'] = $this->media->original_url;
        }
        
        $client = new Client([
        'base_uri' => 'http://asistbot.com/api/',
        'verify' => false
        ]);
        
        $response = $client->post('send.php', ['query' => $data]);
        if( $response->getStatusCode() == '200' ){
          Storage::disk('local')->append('whatsapp.log', now() . " Delivery sent " . $this->number . ' ' . $this->media->original_url ?: '' . "\n");
          return;
        }
        Storage::disk('local')->append('whatsapp.log', "Error");
    }
    
    /**
     * Get the middleware the job should pass through.
     *
     * @return array
     *
    *public function middleware()
    *{
    *return [(new WithoutOverlapping('whatsapp'))->releaseAfter(5)];
    *}
    */
}

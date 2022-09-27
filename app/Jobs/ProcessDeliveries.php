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

class ProcessDeliveries implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $maxExceptions = 2;
    public $backoff = 3;
    
    protected $extension;
    protected $number;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Extension $extension, $number)
    {
        $this->extension = $extension;
        $this->number    = $number;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $apto  = $this->extension->name;
        $admin = $this->extension->admin->name;
        $sms   = "📦*Encomienda Recibida*.📦" . "\n\n";
        $sms   .= "Unidad: *{$admin}*" . "\n";
        $sms   .= "Apartamento: *{$apto}*" . "\n\n";
        $sms   .= "Favor pasar a recogerla. 👍" . "\n\n";
        $sms   .= "Servicio prestado por Phenlinea.com"; 

        $data = [
          "instance_id"  => '632CE66281C2B',
          "access_token" => '3f8b18194536bdafa301c662dc9caa4c',
          "type"         => "text",
          "message"      => $sms,
          "number"       => $this->number
        ];

        $client = new Client([
          'base_uri' => 'http://asistbot.com/api/send.php',
          'verify' => false
        ]);
        
        $response = $client->request('POST', '', ['query' => $data]);

        if( $response->getStatusCode() == '200' ){
          Storage::disk('local')->append('whatsapp.log', implode(",", $data) . "\n");
          return;
        }
        Storage::disk('local')->append('whatsapp.log', "Error");
    }
}

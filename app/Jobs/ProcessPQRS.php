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

class ProcessPQRS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    protected $id;
    protected $status;
    protected $adminName;
    protected $adminPhone;
    protected $media_url;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id, $status, $adminName, $adminPhone, $media_url = null)
    {
        $this->id         = $id;
        $this->status     = $status;
        $this->adminName  = $adminName;
        $this->adminPhone = $adminPhone;
        $this->media_url  = $media_url;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $statuses = [
            'pending'  => 'Pendiente',
            'read'     => 'Leído',
            'answered' => 'Respuesta Enviada',
        ];
        
        $code  = str_pad( $this->id, 4, '0', STR_PAD_LEFT );
        $action = $this->status == 'pending' ? 'registrado' : 'actualizado';
        $admin  = $this->adminName;
        $number = '57' . $this->adminPhone;
        
        $sms  = "Unidad: *{$admin}*" . "\n\n";
        $sms .= "Su *PQRS* ha sido {$action} con éxito. Y su código de seguimiento es el {$code}" . "\n\n";
        $sms .= "Estado: *{$statuses[ $this->status ]}*" . "\n\n";
        $sms .= "Link de seguimiento del estado: (https://phenlinea.com/pqrs/{$code})" . "\n\n";
        $sms .= "Servicio prestado por PHenlinea.com";
        
        $data = [
            "instance_id"  => '63CEC013708B2',
            "access_token" => '3f8b18194536bdafa301c662dc9caa4c',
            "type"         => "text",
            "message"      => $sms,
            "number"       => $number
        ];
        
        if( $this->media ){
            $data['type'] = 'media';
            $data['media_url'] = $this->media_url;
        }
        
        $client = new Client([
            'base_uri' => 'http://asistbot.com/api/',
            'verify' => false
        ]);
        
        $response = $client->post('send.php', ['query' => $data]);
        
        if( $response->getStatusCode() == '200' ){
          Storage::disk('local')->append('whatsapp.log', now() . " PQRS sent " . $number . "\n");
        }else {
          Storage::disk('local')->append('whatsapp.log', "Error");
        }
        
        $data['number'] = '57' . $this->pqrs->phone;
        $response = $client->post('send.php', ['query' => $data]);
        
        if( $response->getStatusCode() == '200' ){
          Storage::disk('local')->append('whatsapp.log', now() . " PQRS sent " . $data['number'] . "\n");
        }
        else {
          Storage::disk('local')->append('whatsapp.log', "Error");
        }
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

<?php

namespace App\Listeners;

use App\Events\GlobalNotificationSent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class SendGlobalSMSNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public $numbers;
    
    public function __construct()
    {
        $this->messages = [
            'services' => 'Los servicios publicos ya se encuentran en Porteria de '    . auth()->user()->admin->name . '. Favor pasar a recogerla. https://phenlinea.com',
            'admin'    => 'La factura de administracion ya se encuentra en Porteria de ' . auth()->user()->admin->name . '. Favor pasar a recogerla. https://phenlinea.com',
        ];
        
        $extensions = auth()->user()->extensions;
        foreach( $extensions as $extension ){
            if( $extension->phone_1 && (substr($extension->phone_1, 0, 1) == '3') ){
                $this->numbers[] = '57' . $extension->phone_1;
            }
            if( $extension->phone_2 && (substr($extension->phone_2, 0, 1) == '3') ){
                $this->numbers[] = '57' . $extension->phone_2;
            }
        }
    }

    /**
     * Handle the event.
     *
     * @param  GlobalNotificationSent  $event
     * @return void
     */
    public function handle(GlobalNotificationSent $event)
    {
        $url = "http://sms.puntodigitalip.com/Api/rest/message";
        $user ="PHENLINEA";
        $password = '1234567';
        
        $sms = $this->messages[ $event->type ];
        $post['text'] = $sms;
        $post['from'] = "PHenlinea";
        $post['to']   = $this->numbers;
        
        $post = json_encode($post);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_HTTPHEADER,[
            "Accept: application/json",
            "Authorization: Basic ".base64_encode("{$user}:{$password}")
        ]);
        $result = curl_exec ($ch);
        $result = json_decode( $result );
        
        if( is_array( $result )  ){
            $result = $result[0];
        }
        
        if( property_exists($result, 'accepted') && $result->accepted ){
            Storage::disk('local')->append('sms_log.txt', $sms . " " . count($this->numbers) );
            return;
        }
        $now = now();
        Storage::disk('local')->append('sms_log.txt', "$now Error al enviar sms $sms");
    }
}

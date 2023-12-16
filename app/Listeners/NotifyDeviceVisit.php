<?php

namespace App\Listeners;

use App\Traits\Devices;
use App\Traits\Whatsapp;
use App\WhatsappClient;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class NotifyDeviceVisit implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */

    public function getMessage($visit){
      $message = "*CONTROL DE VISITANTES* \n\n";
      $message = $message . "Facial✅-QR✅-Clave Temporal✅ \n\n";
      $message = $message . "🏢UNIDAD:  *{$visit->admin->name}* \n";
      $message = $message . "🕒VALIDO PARA *1 INGRESO* \n";
      $message = $message . "🔢CLAVE TEMPORAL:  *{$visit->password}* \n\n";
      $message = $message . "Servicio prestado por PHEnlinea.com";
      return $message;
    }

    public function handle($event)
    {
      if( !$event->visit->admin->device_serial_number ){ return; }

      $whatsapp = new Whatsapp();
      $client   = WhatsappClient::whereEnabled(1)->first();
      
      $whatsapp->send(
        $client->delivery_instance_id,
        '57' . $event->visit->visitor->phone,
        $this->getMessage($event->visit),
        $event->visit->getFirstMediaUrl('qrcode'),
        null
      );
    }
}

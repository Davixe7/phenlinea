<?php

namespace App\Listeners;

use App\Traits\Devices;
use App\Traits\Whatsapp;
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
      $message = $message . "🕒ACCESO VALIDO POR: *{$visit->admin->visits_lifespan} H* \n";
      $message = $message . "🔢CLAVE TEMPORAL:  *{$visit->password}* \n\n";
      $message = $message . "Servicio prestado por PHEnlinea.com";
      return $message;
    }

    public function handle($event)
    {
      if( !$event->visit->admin->device_serial_number ){ return; }
      Storage::append('devices.log', 'Sending message: ' . $this->getMessage($event->visit));

      $whatsapp = new Whatsapp();
      
      $whatsapp->send(
        $event->visit->phone,
        $this->getMessage($event->visit),
        $event->visit->getFirstMediaUrl('qrcode'),
        null
      );
    }
}

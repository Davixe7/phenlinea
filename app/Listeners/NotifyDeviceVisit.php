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
      $message  = 'Autorización válida por ' . $visit->admin->visits_lifespan . ' horas \n';
      $message .= 'Servicio prestado por phenlinea.com';
      return $message;
    }

    public function handle($event)
    {
      $whatsapp = new Whatsapp();
      $whatsapp->send(
        $event->visit->phone,
        $this->getMessage($event->visit),
        'https://phenlinea.com/storage/41665/test.jpg',
        null
      );
    }
}

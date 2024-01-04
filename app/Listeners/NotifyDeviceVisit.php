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

    public function handle($event)
    {
      if( !$event->visit->admin->device_serial_number ){ return; }
      if( !$event->visit->visitor->phone ){ return; }

      $whatsapp = new Whatsapp();
      
      $whatsapp->send([
        'number'    => '57' . $event->visit->visitor->phone,
        'message'   => view('messages.visit', ['visit'=>$event->visit])->render(),
        'media_url' => $event->visit->getFirstMediaUrl('qrcode')
      ]);
    }
}

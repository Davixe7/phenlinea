<?php

namespace App\Listeners;

use App\Traits\Devices;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class CreateDeviceVisit implements ShouldQueue
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
      $devices = new Devices();

      if( $event->visit->visitor->getFirstMedia('picture') ){
        $devices->addFacialTempPwd( $event->visit );
        return;
      }

      $devices->addTempPwd($event->visit);
    }
}

<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use App\Extension;

class SendPasswordSms
{
    use Dispatchable, SerializesModels;
    
    public $extension;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($extension)
    {
      $this->extension = $extension;
    }
}

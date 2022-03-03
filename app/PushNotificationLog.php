<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PushNotificationLog extends Model
{
  protected $fillable = [
    'admin_id',
    'receivers_count',
    'body',
    'sent_to_all',
    'receivers_ids'
  ];
  
  public function extensions(){
      return $this->belongsToMany('App\Extension');
  }
}

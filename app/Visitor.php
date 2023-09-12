<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Visitor extends Model implements HasMedia
{
  use InteractsWithMedia;
  
  protected $fillable = [
    'extension_id',
    'name',
    'dni',
    'authorized_at',
    'plate',
    'phone'
  ];

  public function extension()
  {
    return $this->belongsTo('App\Extension');
  }

  public function checkins()
  {
    return $this->hasMany('App\Checkin');
  }
}

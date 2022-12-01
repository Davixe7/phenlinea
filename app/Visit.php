<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Visit extends Model implements HasMedia
{
  use InteractsWithMedia;
  protected $fillable = [
    'admin_id',
    'arl',
    'checkin',
    'checkout',
    'company',
    'dni',
    'name',
    'eps',
    'extension_id',
    'phone',
    'plate',
    'type',
  ];

  // protected $appends = ['extension_name'];

  public function extension(){
    return $this->belongsTo('App\Extension');
  }

  // public function getExtensionNameAttribute(){
  //   return $this->extension->name;
  // }

}

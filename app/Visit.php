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
    'password',
    'qrcode',
    'start_date',
    'end_date',
    'eps',
    'extension_id',
    'phone',
    'plate',
    'type',
  ];

  protected $casts = [
    'start_date' => 'datetime:Y-m-d H:i:s',
    'end_date'   => 'datetime:Y-m-d H:i:s',
  ];

  public function extension(){
    return $this->belongsTo('App\Extension')->withDefault(['name'=>'ninguna']);
  }
  
  public function admin(){
    return $this->belongsTo(Admin::class);
  }

}

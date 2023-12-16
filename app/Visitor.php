<?php

namespace App;

use App\Traits\Devices;
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
    'phone',
    'company',
    'arl',
    'eps'
  ];

  public function registerMediaCollections(): void
  {
    $this
    ->addMediaCollection('picture')
    ->singleFile();
  }

  public function extension()
  {
    return $this->belongsTo('App\Extension');
  }

  public function checkins()
  {
    return $this->hasMany('App\Checkin');
  }

  public function visits(){
    return $this->hasMany(Visit::class);
  }

  public function getFaceFileBase64(){
    $path    = $this->getFirstMediaPath('picture');
    $type    = pathinfo($path, PATHINFO_EXTENSION);
    $data    = file_get_contents($path);
    return 'data:application/' . $type . ';base64,' . base64_encode($data);
  }
}

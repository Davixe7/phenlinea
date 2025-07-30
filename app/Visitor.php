<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Visitor extends Model implements HasMedia
{
  use InteractsWithMedia, HasFactory;
  
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

  public function registerMediaConversions(?Media $media = null): void
  {
      $this->addMediaConversion('thumb')
            ->width(500)
            ->height(500)
            ->crop('crop-center', 500, 500)
            ->performOnCollections('picture');
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
    $path = $this->getFirstMediaPath('picture');
    if( !file_exists($path) ){ return null; }

    $thumb_path = $this->getFirstMediaPath('picture', 'thumb');
    if( !file_exists($thumb_path) ){
      $this->addMedia( $this->getFirstMediaPath('picture') )->toMediaCollection('picture');
    }
    
    $type    = pathinfo($thumb_path, PATHINFO_EXTENSION);
    $data    = file_get_contents($thumb_path);
    return 'data:application/' . $type . ';base64,' . base64_encode($data);
  }
}

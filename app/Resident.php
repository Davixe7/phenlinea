<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Resident extends Model implements HasMedia
{
  use InteractsWithMedia;
  protected $fillable = [
    'name',
    'email',
    'age',
    'dni',
    'card',
    'is_owner',
    'is_resident',
    'extension_id',
    'is_authorized',
    'disability',
    'device_resident_id',
    'device_synced'
  ];
  protected $hidden   = ['created_at', 'updated_at'];
  protected $appends  = ['picture'];
  
  protected $casts = [
    'is_owner'    => 'integer',
    'is_resident' => 'integer',
    'is_authorized' => 'integer',
    'disability' => 'integer'
  ];

  public function extension(){
    return $this->belongsTo('App\Extension');
  }

  public function scopeUnidad($query, $unit_name){
    return $query->whereHas('admin', function($query) use($unit_name){
      return $query->where('name', 'LIKE', "%" . $unit_name . "%");
    });
  }

  public function vehicles(){
    return $this->hasMany(Vehicle::class);
  }

  public function getPictureAttribute(){
    return $this->getFirstMediaUrl('picture');
  }

  public function registerMediaCollections(): void{
    $this->addMediaCollection('picture')->singleFile();
  }

  public function getTagsAttribute(){
    return $this
    ->vehicles()
    ->whereNotNull('tag')
    ->pluck('tag')
    ->add( $this->card )
    ->filter()
    ->implode(',');
  }
}

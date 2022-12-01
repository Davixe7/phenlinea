<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Petition extends Model implements HasMedia
{
  use InteractsWithMedia;
  protected $fillable = ['extension_id', 'title', 'description', 'email', 'phone', 'status', 'pictures'];
  protected $hidden   = ['updated_at'];
  
  public function admin(){
    return $this->hasOneThrough('App\Admin', 'App\Admin');
  }
  
  public function extension(){
    return $this->belongsTo('App\Extension');
  }
}

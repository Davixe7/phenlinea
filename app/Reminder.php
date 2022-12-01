<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Reminder extends Model implements HasMedia
{
  use InteractsWithMedia;
  protected $fillable = ['admin_id', 'extension_id', 'title', 'description', 'pictures'];
  protected $hidden   = ['updated_at'];
  
  public function admin(){
    return $this->belongsTo('App\Admin');
  }
  
  public function extension(){
    return $this->belongsTo('App\Extension');
  }
  
  public function scopeExtension($query, $extension){
    if(!$extension){
      return $query;
    }
    return $this->where('extension_id', $extension);
  }
}

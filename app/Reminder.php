<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
  protected $fillable = ['admin_id', 'extension_id', 'title', 'description', 'pictures'];
  protected $hidden   = ['updated_at'];
  protected $casts = [
    'pictures'    => 'array'
  ];
  
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

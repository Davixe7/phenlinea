<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Petition extends Model
{
  protected $fillable = ['extension_id', 'title', 'description', 'email', 'phone', 'status', 'pictures'];
  protected $hidden   = ['updated_at'];
  protected $casts    = [
    "pictures" => "array"
  ];
  
  public function admin(){
    return $this->hasOneThrough('App\Admin', 'App\Admin');
  }
  
  public function extension(){
    return $this->belongsTo('App\Extension');
  }
}

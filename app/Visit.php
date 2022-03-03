<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
  protected $fillable = [
    'name', 'dni', 'type', 'arl', 'eps', 'phone',
    'plate', 'checkin', 'checkout', 'admin_id', 'extension_id', 'company'
  ];

  // protected $appends = ['extension_name'];

  public function extension(){
    return $this->belongsTo('App\Extension');
  }

  public function picture(){
    return $this->hasOne('App\Attachment');
  }

  // public function getExtensionNameAttribute(){
  //   return $this->extension->name;
  // }

}

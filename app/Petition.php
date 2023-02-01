<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Petition extends Model implements HasMedia
{
  use InteractsWithMedia;
  protected $fillable = [
      'admin_id',
      'name',
      'apto',
      'description',
      'phone',
      'phone_2',
      'status',
      'answer',
      'read_at',
      'replied_at'
  ];
  
  protected $hidden   = [
      'updated_at'
  ];
  
  public function admin(){
    return $this->belongsTo('App\Admin');
  }
  
}

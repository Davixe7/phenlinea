<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Post extends Model implements HasMedia
{
  use InteractsWithMedia;
  protected $fillable = ['admin_id', 'title', 'description', 'pictures', 'type'];
  protected $hidden   = ['updated_at'];
  
  public function admin(){
    return $this->belongsTo('App\Admin');
  }
  
  public function scopeType($query, $type){
    if( !$type ){
      return $query;
    }
    return $query->whereType($type);
  }
}

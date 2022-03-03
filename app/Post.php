<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
  protected $fillable = ['admin_id', 'title', 'description', 'pictures', 'attachments', 'type'];
  protected $hidden   = ['updated_at'];
  protected $casts = [
    'pictures'    => 'array',
    'attachments' => 'array'
  ];
  
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

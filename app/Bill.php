<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
  protected $fillable = ['admin_id', 'title', 'url'];
  protected $hidden   = ['updated_at'];
  
  public function admin(){
    return $this->belongsTo('App\Admin');
  }
  
}

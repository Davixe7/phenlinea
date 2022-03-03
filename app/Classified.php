<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Classified extends Model
{
  protected $fillable = [
    'name', 'description', 'pictures', 'phone_1', 'phone_2', 'state', 'city', 'address', 'email', 'price'
  ];
  
  public function pictures(){
    return $this->hasMany('App\Attachment');
  }
  
  public function scopeState($query, $state){
    if( !$state ){
      return $query;
    }
    return $query->whereState($state);
  }
  
  public function scopeCity($query, $city){
    if( !$city ){
      return $query;
    }
    return $query->whereCity($city);
  }
}

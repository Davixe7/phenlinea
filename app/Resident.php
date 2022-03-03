<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Resident extends Model
{
  protected $fillable = [
    'name',
    'age',
    'dni',
    'card',
    'is_owner',
    'is_resident',
    'extension_id',
    'is_authorized',
    'disability'
  ];
  protected $hidden   = ['created_at', 'updated_at'];
  protected $casts = [
    'is_owner'    => 'integer',
    'is_resident' => 'integer',
    'is_authorized' => 'integer',
    'disability' => 'integer'
  ];

  public function extension(){
    return $this->belongsTo('App\Extension');
  }

  public function scopeUnidad($query, $unit_name){
    return $query->whereHas('admin', function($query) use($unit_name){
      return $query->where('name', 'LIKE', "%" . $unit_name . "%");
    });
  }
}

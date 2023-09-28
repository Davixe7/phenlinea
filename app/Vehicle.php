<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
  protected $fillable = [
    'extension_id',
    'resident_id',
    'type',
    'plate',
    'tag'
  ];

  use HasFactory;

  public function extension(){
    return $this->belongsTo(Extension::class);
  }

  public function resident(){
    return $this->belongsTo(Resident::class);
  }
}
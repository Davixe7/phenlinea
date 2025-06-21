<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Petition extends Model implements HasMedia
{
  use HasFactory;
  use InteractsWithMedia;
  protected $fillable = [
    'admin_id',
    'answer',
    'extension_name',
    'description',
    'name',
    'phone',
    'phone_2',
    'read_at',
    'replied_at',
    'status'
  ];

  protected $hidden   = [
    'updated_at'
  ];

  public function admin()
  {
    return $this->belongsTo('App\Admin');
  }
}

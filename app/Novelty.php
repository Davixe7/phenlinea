<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Novelty extends Model implements HasMedia
{
  use InteractsWithMedia, HasFactory;

  protected $fillable = ['title', 'description', 'porteria_id', 'read_at'];
  protected $hidden   = ['updated_at'];
  protected $appends  = ['excerpt'];
  protected $casts    = [
    'excerpt'  => 'string',
    'read'     => 'integer'
  ];

  public function porteria(){
    return $this->belongsTo('App\Porteria');
  }

  public function getExcerptAttribute(){
    return substr($this->description, 0, 80) . '...';
  }

}

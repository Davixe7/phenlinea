<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
  use InteractsWithMedia;
  protected $fillable = ['name', 'description', 'price', 'category_id', 'store_id'];
  
  protected $appends = ['excerpt'];
  
  public function getExcerptAttribute(){
    return Str::substr($this->description, 0, 32);
  }
}

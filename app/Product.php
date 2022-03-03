<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
  protected $fillable = ['name', 'description', 'price', 'category_id', 'store_id'];
  
  protected $appends = ['main_picture', 'excerpt'];
  
  public function pictures(){
    return $this->hasMany('App\Attachment');
  }
  
  public function getMainPictureAttribute(){
    return $this->pictures()->first();
  }
  
  public function getExcerptAttribute(){
      return Str::substr($this->description, 0, 32);
  }
}

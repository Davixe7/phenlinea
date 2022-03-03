<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Novelty extends Model
{
  protected $fillable = ['title', 'description', 'porteria_id'];
  protected $hidden   = ['updated_at'];
  protected $appends  = ['excerpt','pictures_url'];
  protected $casts     = [
    'pictures' => 'array',
    'excerpt'  => 'string',
    'read'     => 'integer'
  ];

  public function porteria(){
    return $this->belongsTo('App\Porteria');
  }

  public function getExcerptAttribute(){
    return substr($this->description, 0, 80) . '...';
  }

  public function getPicturesUrlAttribute(){
    return collect($this->pictures)->map(function($p){
      return asset( $p['url'] );
    });
  }
}

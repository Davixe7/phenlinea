<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Store extends Authenticatable
{
  protected $fillable = [
    'name', 'description', 'nit',
    'phone_1', 'phone_2', 'email',
    'address', 'lat', 'lng',
    'logo', 'pictures', 'category', 'schedule', '_email', 'api_token','password','_password'
  ];
  
  protected $appends = ['qr', 'profile_picture', 'permalink'];
  
  protected $hidden = ['created_at', 'updated_at'];
  
  protected $casts = [
    'logo' => 'array',
    'schedule' => 'array',
    'pictures' => 'array'
  ];
  
  public function menu(){
    return $this->hasMany('App\Product');
  }
  
  public function getQrAttribute(){
    $path = public_path("qr/store_{$this->id}_qr.svg");
    if( !is_file( $path ) ){
      \QrCode::size(500)
        ->format('svg')
        ->generate( $this->permalink, $path);
    }
    return [
      'path' => $path,
      'url'  => url("qr/store_{$this->id}_qr.svg")
    ];
  }
  
  public function getProfilePictureAttribute(){
    if( $this->pictures && count($this->pictures) ){
      return $this->pictures[0];
    }
    return null;
  }
  
  public function getPermalinkAttribute(){
    return url("stores/$this->id");
  }
  
  public function resetPassword(){
    $password = mt_rand(100000000000, 999999999999) . '';
    $this->update([
      '_password' => $password,
      'password'  => bcrypt($password)
    ]);
  }
}

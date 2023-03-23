<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Extension extends Authenticatable implements HasMedia
{
  use InteractsWithMedia;
  
  protected $fillable = [
    'name',
    'password',
    'phone_1',
    'phone_2',
    'phone_3',
    'phone_4',
    'admin_id',
    'owner_phone',
    'owner_name',
    'emergency_contact',
    'emergency_contact_name',
    'pets_count',
    'has_own_parking',
    'parking_number1',
    'parking_number2',
    'deposit',
    'vehicles',
    'observation',
    'email',
    '_email',
    '_password',
    'password'
  ];

  protected $hidden   = [
    'created_at',
    'updated_at'
  ];
  
  protected $casts    = [
    'has_deposit'     => 'integer',
    'has_own_parking' => 'integer',
    'vehicles'        => 'array',
    'phones'          => 'array'
  ];
  
  protected $appends = [
    'parking_numbers_str'
  ];

  public function resetPassword(){
    $password = mt_rand(100000000000, 999999999999) . '';
    $this->update([
      '_email'    => ($this->_email) ?: $this->admin_id . $this->name . "@phenlinea.com",
      '_password' => $password,
      'password'  => bcrypt($password)
    ]);
  }

  public function username(){
    return '_email';
  }

  public function admin(){
    return $this->belongsTo('App\Admin');
  }

  public function residents(){
    return $this->hasMany('App\Resident');
  }

  public function posts(){
    return $this->hasMany('App\Post', 'admin_id', 'admin_id');
  }

  public function reminders(){
    return $this->hasMany('App\Reminder');
  }

  public function bills(){
    return $this->hasMany('App\Bill', 'admin_id', 'admin_id');
  }

  public function petitions(){
    return $this->hasMany('App\Petition');
  }
  
  public function visitors(){
    return $this->hasMany('App\Visitor');
  }
  
  public function checkins(){
    return $this->hasMany('App\Checkin');
  }
  
  public function device_tokens(){
    return $this->hasMany('App\DeviceToken');
  }
  
  public function push_notification_logs(){
      return $this->belongsToMany('App\PushNotificationLog');
  }

  public function scopeName($query, $name){
    if(!$name){
      return $query;
    }
    return $query->where('name', $name);
  }

  public function scopeNameLike($query, $name){
    return $query->where('name', 'LIKE', '%' . $name . '%');
  }

  public function scopeUnidad($query, $unidad){
    return $query->whereHas('admin', function($query) use($unidad){
      return $query->where('name', 'LIKE', '%' . $unidad . '%');
    });
  }

  public function scopePhone($query, $number){
    if(!$number){
      return $query;
    }
    return $query->where('phone_1', $number)->orWhere('phone_2', $number);
  }

  public function getAdultsAttribute(){
    $adults = $this->residents()->where('age', '>=', 18)->count();
    return $adults;
  }

  public function getMinorsAttribute(){
    $minors = $this->residents()->where('age', '<', 18)->count();
    return $minors;
  }

  public function getPhonesAttribute(){
    $fields = ['phone_1', 'phone_2', 'phone_3', 'phone_4', 'owner_phone'];
    return
    collect($fields)->map(function($field){
      return $this->{ $field };
    })
    ->reject(function($phone){
      return !$phone;
    })
    ->toArray();
  }

  public function getParkingNumbersStrAttribute(){
    if( !( $this->parking_number1 || $this->parking_number2 ) ){ return "Ninguno"; }
    return "$this->parking_number1 - $this->parking_number2";
  }
  
  public function getPlatesAttribute(){
    if( !$this->vehicles || !count($this->vehicles) ){ return "Ninguno"; }
    $plates = '';
    collect($this->vehicles)->each(function($v)use(&$plates){
      $plates .= $v['plate'] . " ";
    });
    return str_replace(" "," - ",trim($plates));
  }
  
  public function getValidWhatsappPhoneNumbersAttribute(){
    $numbers = collect([$this->phone_1, $this->phone_2, $this->phone_3, $this->phone_4]);
    $numbers = $numbers->filter(function($number){
        return $number && $number[0] == '3';
    });
    return $numbers->take(2)->toArray();
  }
}

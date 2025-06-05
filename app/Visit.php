<?php

namespace App;

use App\Traits\Devices;
use App\Traits\Whatsapp;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;

class Visit extends Model implements HasMedia
{
  use InteractsWithMedia, Notifiable;

  protected $fillable = [
    'admin_id',
    'checkin',
    'checkout',
    'password',
    'start_date',
    'end_date',
    'extension_id',
    'extension_name',
    'visitor_id',
    'plate',
    'note',
    'authorized_by'
  ];

  protected $casts = [
    'start_date' => 'datetime:Y-m-d H:i:s',
    'end_date'   => 'datetime:Y-m-d H:i:s',
    'checkin'    => 'datetime:Y-m-d H:i:s',
    'checkout'   => 'datetime:Y-m-d H:i:s',
  ];

  public function routeNotificationForMeta(Notification $notification): string|null {
    if( $this->admin_id == 1 || $this->visitor->dni == '30123456'){
      return '584147912134';
    }

    return $this->visitor->phone ? '57' . $this->visitor->phone : null;
  }

  public function registerMediaCollections(): void
  {
    $this
    ->addMediaCollection('qrcode')
    ->singleFile();
  }

  public function extension(){
    return $this->belongsTo('App\Extension')->withDefault(['name'=>'ninguna']);
  }
  
  public function admin(){
    return $this->belongsTo(Admin::class);
  }

  public function visitor(){
    return $this->belongsTo(Visitor::class);
  }

  public function addPwd(){
    if( !$this->admin->device_serial_number || !$this->admin->device_community_id ){
      return;
    }

    $devices = new Devices();

    if( $this->visitor->getFirstMediaPath('picture') ){
      $devices->addFacialTempPwd($this);
      return;
    }
    $devices->addTempPwd($this);
  }

  public function _notifyDeviceVisit(){
    if( !$this->admin->device_serial_number ){ return; }
    if( !$this->visitor->phone ){ return; }

    $whatsapp = new Whatsapp();
    
    $whatsapp->send([
      'number'    => '57' . $this->visitor->phone,
      'message'   => view('messages.visit', ['visit'=>$this])->render(),
      'media_url' => $this->getFirstMediaUrl('qrcode')
    ]);
  }

}

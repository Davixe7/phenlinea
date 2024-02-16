<?php

namespace App;

use App\Traits\Whatsapp;
use Exception;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\CanResetPassword;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use GuzzleHttp\Client;

class Admin extends Authenticatable implements MustVerifyEmail, CanResetPassword, HasMedia
{
  use Notifiable;
  use InteractsWithMedia;

  protected $fillable = [
    'name',
    'address',
    'contact_email',
    'device_serial_number',
    'device_2_serial_number',
    'device_community_id',
    'device_building_id',
    'email',
    'nit',
    'password',
    'phone',
    'phone_2',
    'phone_verification',
    'status',
    'visits_lifespan',
    'whatsapp_instance_id',
    'whatsapp_status',
    'whatsapp_group_id',
    'whatsapp_group_url'
  ];

  protected $hidden  = ['password', 'created_at', 'updated_at'];
  protected $without = ['extensions'];
  protected $appends = ['solvencia'];
  protected $casts = [
    'solvencia'   => 'string',
  ];

  public function registerMediaCollections(): void
  {
    $this->addMediaCollection('whatsapp_qr')->singleFile();

    $this->addMediaCollection('picture')->singleFile();
  }

  public function invoices()
  {
    return $this->hasMany('App\Invoice', 'nit', 'nit');
  }

  public function visits()
  {
    return $this->hasMany('App\Visit');
  }

  public function extensions()
  {
    return $this->hasMany('App\Extension');
  }

  public function payments()
  {
    return $this->hasMany('App\Payment');
  }

  public function residents()
  {
    return $this->hasManyThrough('App\Resident', 'App\Extension');
  }

  public function novelties()
  {
    return $this->hasManyThrough('App\Novelty', 'App\Porteria');
  }

  public function posts()
  {
    return $this->hasMany('App\Post');
  }

  public function reminders()
  {
    return $this->hasMany('App\Reminder');
  }

  public function bills()
  {
    return $this->hasMany('App\Bill');
  }

  public function petitions()
  {
    return $this->hasMany('App\Petition');
  }

  public function porterias()
  {
    return $this->hasMany('App\Porteria');
  }

  public function modules()
  {
    return $this->belongsToMany('App\Module');
  }

  public function push_notification_logs()
  {
    return $this->hasMany('App\PushNotificationLog');
  }

  public function batch_messages()
  {
    return $this->hasMany('App\BatchMessage');
  }

  public function resident_invoices(){
    return $this->hasMany(ResidentInvoice::class);
  }

  public function getSolvenciaAttribute()
  {
    $month = "m" . date('n');
    $lastPayment = $this->payments()->current()->first();
    if ($lastPayment) {
      return $lastPayment->attributes[$month];
    }
    return null;
  }

  public function getExtensionsCountAttribute()
  {
    return $this->extensions->count();
  }

  public function getPhonesAttribute()
  {
    $fields = ['phone', 'phone_2'];
    return
      collect($fields)->map(function ($field) {
        return $this->{$field};
      })
      ->reject(function ($phone) {
        return $phone == false;
      });
  }

  public function resetPassword()
  {
    $password = mt_rand(100000000000, 999999999999) . '';
    $this->update([
      'password'  => bcrypt($password)
    ]);
  }

  public function facturas()
  {
    return $this->hasMany(ResidentInvoice::class);
  }

  public function resident_invoice_batches(){
    return $this->hasMany(ResidentInvoiceBatch::class);
  }

  public function getBatches(){
     $http     = new Client();
     $query    = ['user_id' => auth()->id(), 'type'=>'batch'];
     $response = $http->get("https://api.phenlinea.com/api/batches/", compact('query'));
     return json_decode($response->getBody(), true)['data'];
  }

  public function hasValidInstance(){
    if( !$this->whatsapp_instance_id ){ return false; }
    $whatsapp = new Whatsapp( WhatsappClient::find(1) );
    try { return $whatsapp->validateInstance($this->whatsapp_instance_id, $this->phone);}
    catch(Exception $e){ return false; }
  }
}

<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\CanResetPassword;

class Admin extends Authenticatable implements MustVerifyEmail, CanResetPassword
{
  use Notifiable;

  protected $fillable = [
    'name',
    'address',
    'contact_email',
    'email',
    'nit',
    'password',
    'phone',
    'phone_2',
    'phone_verification',
    'picture',
    'slug',
    'status',
    'whatsapp_instance_id',
    'whatsapp_status',
    'whatsapp_group_id',
    'whatsapp_group_url'
  ];

  protected $hidden   = ['password', 'created_at', 'updated_at'];
  protected $without = ['extensions'];
  protected $appends = ['solvencia'];
  protected $casts = [
    'solvencia'   => 'string',
  ];

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

  public function whatsapp_messages_batches()
  {
    return $this->hasMany('App\WhatsappMessagesBatch');
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
    return $this->hasMany('App\Factura');
  }
}

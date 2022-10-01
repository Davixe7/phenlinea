<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\VerifyPhone;
use Illuminate\Contracts\Auth\CanResetPassword;
use App\Notifications\PasswordResetNotificationSms;

class Admin extends Authenticatable implements MustVerifyEmail, CanResetPassword
{
  use Notifiable;

  protected $fillable = [
    'name',
    'email',
    'password',
    'contact_email',
    'nit',
    'sms_enabled',
    'phone',
    'phone_2',
    'address',
    'referer_id',
    'status',
    'picture',
    'phone_verification',
    'wa_instance_id'
  ];

  protected $hidden   = ['password', 'created_at', 'updated_at'];
  protected $without = ['extensions'];
  protected $appends = ['solvencia'];
  protected $casts = [
    'solvencia'   => 'string',
    'sms_enabled' => 'integer'
  ];

  public function getEmailForVerification()
  {
    return $this->contact_email;
  }

  public function sendEmailVerificationNotification()
  {
    $this->notify(new VerifyPhone());
  }

  public function sendPasswordResetNotification($token)
  {
    $this->notify(new PasswordResetNotificationSms($token));
  }

  public function invoices()
  {
    return $this->hasMany('App\Invoice', 'nit', 'nit');
  }

  public function visits()
  {
    return $this->hasMany('App\Visit');
  }

  public function notifications()
  {
    return $this->hasMany('App\Notification');
  }

  public function logs()
  {
    return $this->hasManyThrough('App\Notification', 'App\Porteria');
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
    return $this->hasManyThrough('App\Petition', 'App\Extension');
  }

  public function referer()
  {
    return $this->hasMany('App\Freelancer', 'referer_id');
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

  public function getSolvenciaAttribute()
  {
    $month = "m" . date('n');
    $lastPayment = $this->payments()->current()->first();
    if ($lastPayment) {
      return $lastPayment->attributes[$month];
    }
    return null;
  }

  public function getMonthSmsCountAttribute()
  {
    return $this->notifications()->bulk()->sentThisMonth()->count();
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

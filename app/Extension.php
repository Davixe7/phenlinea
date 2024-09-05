<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Extension extends Authenticatable implements HasMedia
{
  use InteractsWithMedia;

  protected $guarded = ['id'];

  protected $hidden   = [
    'created_at',
    'updated_at'
  ];

  protected $casts    = [
    'has_deposit'     => 'integer',
    'has_own_parking' => 'integer',
    'phones'          => 'array'
  ];

  protected $appends = [
    // 'parking_numbers_str'
  ];

	public function registerMediaCollections(): void
	{
	    $this
	        ->addMediaCollection('deliveries')
	        ->singleFile();
	}

  public function resident_invoices()
  {
    return $this->hasMany(ResidentInvoice::class);
  }

  public function admin()
  {
    return $this->belongsTo('App\Admin');
  }

  public function residents()
  {
    return $this->hasMany('App\Resident');
  }

  public function posts()
  {
    return $this->hasMany('App\Post', 'admin_id', 'admin_id');
  }

  public function reminders()
  {
    return $this->hasMany('App\Reminder');
  }

  public function bills()
  {
    return $this->hasMany('App\Bill', 'admin_id', 'admin_id');
  }

  public function petitions()
  {
    return $this->hasMany('App\Petition');
  }

  public function visitors()
  {
    return $this->hasMany('App\Visitor');
  }

  public function checkins()
  {
    return $this->hasMany('App\Checkin');
  }

  public function device_tokens()
  {
    return $this->hasMany('App\DeviceToken');
  }

  public function push_notification_logs()
  {
    return $this->belongsToMany('App\PushNotificationLog');
  }

  public function vehicles()
  {
    return $this->hasMany(Vehicle::class);
  }

  public function phoneOwner()
  {
    return $this->belongsTo(Resident::class, 'resident_id', 'id')->withDefault(['name' => 'Desconocido']);
  }
  public function phoneOwner2()
  {
    return $this->belongsTo(Resident::class, 'resident_id_2', 'id')->withDefault(['name' => 'Desconocido']);;
  }
  public function phoneOwner3()
  {
    return $this->belongsTo(Resident::class, 'resident_id_3', 'id')->withDefault(['name' => 'Desconocido']);;
  }
  public function phoneOwner4()
  {
    return $this->belongsTo(Resident::class, 'resident_id_4', 'id')->withDefault(['name' => 'Desconocido']);;
  }

  public function getAdultsAttribute()
  {
    $adults = $this->residents()->where('age', '>=', 18)->count();
    return $adults;
  }

  public function getMinorsAttribute()
  {
    $minors = $this->residents()->where('age', '<', 18)->count();
    return $minors;
  }

  public function getPhonesAttribute()
  {
    return collect($this->phone_1, $this->phone_2, $this->phone_3, $this->phone_4, $this->owner_phone)
      ->reject(fn ($phone) => !$phone)
      ->toArray();
  }

  public function getParkingNumbersStrAttribute()
  {
    if (!($this->parking_number1 || $this->parking_number2)) {
      return "Ninguno";
    }
    return "$this->parking_number1 - $this->parking_number2";
  }

  public function getPlatesAttribute()
  {
    return $this->vehicles()->pluck('plate')->toArray();
  }

  public function getValidWhatsappPhoneNumbersAttribute()
  {
    $phonesPerExtension = $this->admin_id == 81 ? 1 : 2;
    return collect([$this->phone_1, $this->phone_2, $this->phone_3, $this->phone_4])
      ->reject(fn ($phone) => !$phone || $phone[0] != '3')
      ->take($phonesPerExtension)
      ->toArray();
  }

  public function scopeName($query, $name)
  {
    return !$name ? $query : $query->where('name', $name);
  }

  public function scopeNameLike($query, $name)
  {
    return $query->where('name', 'LIKE', '%' . $name . '%');
  }

  public function scopeUnidad($query, $unidad)
  {
    return $query->whereHas('admin', function ($query) use ($unidad) {
      return $query->where('name', 'LIKE', '%' . $unidad . '%');
    });
  }

  public function scopePhone($query, $number)
  {
    return !$number
      ? $query
      : $query->where('phone_1', $number)->orWhere('phone_2', $number);
  }

  public static function getStoreAttributes(){
    return [
      'name',
      'phone_1',
      'phone_2',
      'phone_3',
      'phone_4',
      'resident_id',
      'resident_id_2',
      'resident_id_3',
      'resident_id_4',
      'owner_name',
      'owner_phone',
      'pets_count',
      'has_own_parking',
      'parking_number1',
      'parking_number2',
      'deposit',
      'observation',
      'emergency_contact',
      'emergency_contact_name',
      'email',
      'api_token'
    ];
  }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Porteria extends Authenticatable implements JWTSubject
{
  use HasFactory;

  protected $fillable = ['name', 'email', 'password', 'admin_id'];
  protected $hidden   = ['password', 'created_at', 'updated_at'];

  /**
   * Get the identifier that will be stored in the subject claim of the JWT.
   *
   * @return mixed
   */
  public function getJWTIdentifier()
  {
    return $this->getKey();
  }

  /**
   * Return a key value array, containing any custom claims to be added to the JWT.
   *
   * @return array
   */
  public function getJWTCustomClaims()
  {
    return [];
  }

  // protected $without = ['extensions'];

  public function extensions()
  {
    return $this->hasMany('App\Extension', 'admin_id', 'admin_id');
  }

  public function visits()
  {
    return $this->hasMany(Visit::class, 'admin_id', 'admin_id');
  }

  public function novelties()
  {
    return $this->hasMany('App\Novelty');
  }

  public function admin()
  {
    return $this->belongsTo('App\Admin');
  }

  public function checkins()
  {
    return $this->hasManyThrough('App\Checkin', 'App\Extension', 'admin_id', 'extension_id', 'admin_id');
  }

  public function vehicles()
  {
    return $this->hasManyThrough(Vehicle::class, Extension::class, 'admin_id', 'extension_id', 'admin_id', 'id');
  }
}

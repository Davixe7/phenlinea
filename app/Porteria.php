<?php

namespace App;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Porteria extends Authenticatable implements JWTSubject
{
  use HasFactory;

  protected $guarded = ['id'];
  protected $hidden  = ['password', 'created_at', 'updated_at'];

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

  public function extensions()
  {
    return $this->hasMany(Extension::class, 'admin_id', 'admin_id');
  }

  public function visits()
  {
    return $this->hasMany(Visit::class, 'admin_id', 'admin_id');
  }

  public function novelties()
  {
    return $this->hasMany(Novelty::class);
  }

  public function admin()
  {
    return $this->belongsTo(Admin::class);
  }

  public function checkins()
  {
    return $this->hasManyThrough(Checkin::class, Extension::class, 'admin_id', 'extension_id', 'admin_id');
  }

  public function vehicles()
  {
    return $this->hasManyThrough(Vehicle::class, Extension::class, 'admin_id', 'extension_id', 'admin_id', 'id');
  }
}

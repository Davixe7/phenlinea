<?php

namespace App;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Porteria extends Authenticatable implements JWTSubject
{

  protected $fillable = ['name', 'email', 'password', 'admin_id'];
  protected $hidden   = ['password', 'created_at', 'updated_at'];
  protected $appends  = ['extensions_count'];

  // Rest omitted for brevity

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

  public function notifications(){
      return $this->hasMany('App\Notification');
  }

  public function extensions(){
    return $this->hasMany('App\Extension', 'admin_id', 'admin_id');
  }

  public function getExtensionsCountAttribute(){
    return $this->extensions()->count();
  }

  public function novelties(){
    return $this->hasMany('App\Novelty');
  }

  public function admin(){
    return $this->belongsTo('App\Admin');
  }
  
  public function checkins(){
    return $this->hasManyThrough('App\Checkin', 'App\Extension', 'admin_id', 'extension_id', 'admin_id');
  }

  public function daysSinceLastNotification(String $type){
    $lastNotification     = $this->notifications()->where('type', $type)->orderBy('date', 'DESC')->first();
    $lastNotificationDate = ($lastNotification) ? \Carbon\Carbon::parse($lastNotification->date) : null;
    $now = \Carbon\Carbon::now(new \DateTimeZone('America/Bogota'));
    $sinceLastNot = ($lastNotificationDate) ? $lastNotificationDate->diffInDays( $now ) : null;
    return $sinceLastNot;
  }
}

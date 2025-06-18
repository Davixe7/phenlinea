<?php

namespace App;

use GuzzleHttp\Client;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, HasRoles, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getEmailForVerification(){
      return $this->email;
    }

    public function whatsapp_clients(){
      return $this->hasMany('App\WhatsappClient');
    }

    public function getBatches(){
      $http     = new Client();
      $query    = ['user_id' => auth()->id(), 'type'=>'batch'];
      $response = $http->get("https://api.phenlinea.com/api/batches/", compact('query'));
      return json_decode($response->getBody(), true)['data'];
    }
}

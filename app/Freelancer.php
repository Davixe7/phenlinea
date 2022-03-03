<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Freelancer extends Authenticatable
{
    protected $fillable = ['name', 'email', 'password', 'rate', 'phone'];
    protected $hidden = ['password', 'created_at', 'updated_at'];
    protected $appends = ['referrals_count'];
    
    public function admins(){
      return $this->hasMany('App\Admin', 'referer_id');
    }
    
    public function getReferralsCountAttribute(){
      return $this->admins()->count();
    }
}

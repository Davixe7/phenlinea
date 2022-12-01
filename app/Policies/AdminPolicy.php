<?php

namespace App\Policies;

use App\User;
use App\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdminPolicy
{
  use HandlesAuthorization;

  public function before($user)
  {
    if( Auth::guard('web')->check() || Auth::guard('api')->check() ){
      return true;
    }
    if( Auth::guard('porteria')->check() ){
      return false;
    }
  }

  public function viewAny($user)
  {
    return false;
  }

  public function view($user, Admin $admin)
  {
    if( $user->id == $admin->id ){
      return true;
    }
  }

  public function create($user)
  {
    return false;
  }

  public function update($user, Admin $admin)
  {
    if( $user->id == $admin->id ){
      return true;
    }
  }

  public function delete($user, Admin $admin)
  {
    if( $user->id == $admin->id ){
      return true;
    }
  }

}

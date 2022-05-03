<?php

namespace App\Policies;

use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
  use HandlesAuthorization;

  /**
  * Determine whether the user can view the model.
  *
  * @param  \App\User  $user
  * @param  \App\User  $model
  * @return mixed
  */

  public function viewAny($user)
  {
    return (Auth::guard('web')->check() || Auth::guard('api')->check()) ? true : false;
  }

  public function view(User $user, User $model)
  {
    return Auth::check() || Auth::guard('api')->check();
  }

  /**
  * Determine whether the user can create models.
  *
  * @param  \App\User  $user
  * @return mixed
  */
  public function create(User $user)
  {
    return Auth::check() || Auth::guard('api')->check();
  }

  /**
  * Determine whether the user can update the model.
  *
  * @param  \App\User  $user
  * @param  \App\User  $model
  * @return mixed
  */
  public function update(User $user, User $model)
  {
    return Auth::check() && Auth::id() == $model->id ||
           Auth::guard('api')->check() && Auth::guard('api')->id() == $model->id;
  }

  /**
  * Determine whether the user can delete the model.
  *
  * @param  \App\User  $user
  * @param  \App\User  $model
  * @return mixed
  */
  public function delete(User $user, User $model)
  {
    return Auth::check() && Auth::id() == $model->id ||
           Auth::guard('api')->check() && Auth::guard('api')->id() == $model->id;
  }

  /**
  * Determine whether the user can restore the model.
  *
  * @param  \App\User  $user
  * @param  \App\User  $model
  * @return mixed
  */
  public function restore(User $user, User $model)
  {
    //
  }

  /**
  * Determine whether the user can permanently delete the model.
  *
  * @param  \App\User  $user
  * @param  \App\User  $model
  * @return mixed
  */
  public function forceDelete(User $user, User $model)
  {
    //
  }
}

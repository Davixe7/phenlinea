<?php

namespace App\Policies;

use App\User;
use App\Novelty;
use Illuminate\Auth\Access\HandlesAuthorization;

class NoveltyPolicy
{
  use HandlesAuthorization;
  
  /**
  * Determine whether the user can view the extension.
  *
  * @param  \App\User  $user
  * @param  \App\Novelty  $novelty
  * @return mixed
  */
  
  public function before($user){
    if( auth()->getDefaultDriver() == 'web' ){
      return true;
    }
  }
  
  public function view($user, Novelty $novelty)
  {
    return $user->novelties()->find($novelty->id)->get();
  }
  
  /**
  * Determine whether the user can create extensions.
  *
  * @param  \App\User  $user
  * @return mixed
  */
  
  public function create($user)
  {
    return in_array(auth()->getDefaultDriver(), ['api-porteria', 'porteria']);
  }
  
  public function index()
  {
    return true;
  }
  
  /**
  * Determine whether the user can update the extension.
  *
  * @param  \App\User  $user
  * @param  \App\Novelty  $novelty
  * @return mixed
  */
  public function update($user, Novelty $novelty)
  {
    $isPorteria = in_array(auth()->getDefaultDriver(), ['api-porteria', 'porteria']);
    $isOwner    = $user->novelties()->find($novelty->id);
    return ($isPorteria && $isOwner);
  }
  
  /**
  * Determine whether the user can delete the extension.
  *
  * @param  \App\User  $user
  * @param  \App\Novelty  $novelty
  * @return mixed
  */
  public function delete($user, Novelty $novelty)
  {
    $isPorteria = in_array(auth()->getDefaultDriver(), ['api-porteria', 'porteria']);
    $isOwner    = $user->novelties()->find($novelty->id);
    return ($isPorteria && $isOwner);
  }
  
  public function markAsRead($user, Novelty $novelty){
    $isAdmin = in_array(auth()->getDefaultDriver(), ['api-admin', 'admin']);
    $isOwner    = $user->novelties()->find($novelty->id);
    return ($isAdmin && $isOwner);
  }
  
}

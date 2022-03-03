<?php

namespace App\Policies;

use App\User;
use App\Porteria;
use Illuminate\Auth\Access\HandlesAuthorization;

class PorteriaPolicy
{
  use HandlesAuthorization;
  
  /**
  * Determine whether the user can view the porteria.
  *
  * @param  \App\User  $user
  * @param  \App\Porteria  $porteria
  * @return mixed
  */
  public function before($user){
    $driver = auth()->getDefaultDriver();
    if( in_array($driver, ['web', 'api']) ){
      return true;
    }
  }
  
  public function index($user)
  {
    $driver = auth()->getDefaultDriver();
    if( in_array($driver, ['web', 'api']) ){
      return true;
    }
  }
  
  public function view($user, Porteria $porteria)
  {
    return $user->id == $porteria->id;
  }
  
  /**
  * Determine whether the user can create porterias.
  *
  * @param  \App\User  $user
  * @return mixed
  */
  public function create($user)
  {
    //
  }
  
  /**
  * Determine whether the user can update the porteria.
  *
  * @param  \App\User  $user
  * @param  \App\Porteria  $porteria
  * @return mixed
  */
  public function update($user, Porteria $porteria)
  {
    //
  }
  
  /**
  * Determine whether the user can delete the porteria.
  *
  * @param  \App\User  $user
  * @param  \App\Porteria  $porteria
  * @return mixed
  */
  public function delete($user, Porteria $porteria)
  {
    //
  }
  
  /**
  * Determine whether the user can restore the porteria.
  *
  * @param  \App\User  $user
  * @param  \App\Porteria  $porteria
  * @return mixed
  */
  public function restore($user, Porteria $porteria)
  {
    //
  }
  
  /**
  * Determine whether the user can permanently delete the porteria.
  *
  * @param  \App\User  $user
  * @param  \App\Porteria  $porteria
  * @return mixed
  */
  public function forceDelete($user, Porteria $porteria)
  {
    //
  }
  
  public function extension($user, Porteria $porteria, Extension $extension)
  {
    return $user->id == $poteria->id && $user->extensions()->find($extension->id);
  }
}

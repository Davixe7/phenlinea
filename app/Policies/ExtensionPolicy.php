<?php

namespace App\Policies;

use App\User;
use App\Extension;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExtensionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the extension.
     *
     * @param  \App\User  $user
     * @param  \App\Extension  $extension
     * @return mixed
     */
    
    public function view($user, Extension $extension)
    {
      return $user->extensions()->find( $extension->id );
    }
    
    public function index($user)
    {
      return in_array( auth()->getDefaultDriver(), ['admin', 'api-admin'] );
    }
    
    /**
     * Determine whether the user can create extensions.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    
    public function create($user)
    {
      return in_array( auth()->getDefaultDriver(), ['admin', 'api-admin'] );
    }

    /**
     * Determine whether the user can update the extension.
     *
     * @param  \App\User  $user
     * @param  \App\Extension  $extension
     * @return mixed
     */
    public function update($user, Extension $extension)
    {
      $isAdmin = in_array( auth()->getDefaultDriver(), ['admin', 'api-admin'] );
      $isOwner = $user->extensions()->find( $extension->id );
      return ($isAdmin && $isOwner);
    }

    /**
     * Determine whether the user can delete the extension.
     *
     * @param  \App\User  $user
     * @param  \App\Extension  $extension
     * @return mixed
     */
    public function delete($user, Extension $extension)
    {
      $isAdmin = in_array( auth()->getDefaultDriver(), ['admin', 'api-admin'] );
      $isOwner = $user->extensions()->find( $extension->id );
      return ($isAdmin && $isOwner);
    }
    
    public function import($user){
      return in_array( auth()->getDefaultDriver(), ['admin', 'api-admin'] );
    }

}

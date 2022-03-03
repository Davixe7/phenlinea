<?php

namespace App\Policies;

use App\User;
use App\Petition;
use Illuminate\Auth\Access\HandlesAuthorization;

class PetitionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the petition.
     *
     * @param  \App\ $user
     * @param  \App\Petition  $petition
     * @return mixed
     */

    public function viewAny(){
      return true;
    }

    public function view($user, Petition $petition)
    {
      return $user->petitions()->find( $petition->id )->get();
    }

    /**
     * Determine whether the user can create petitions.
     *
     * @param  \App\ $user
     * @return mixed
     */
    public function create($user)
    {
      return in_array( auth()->getDefaultDriver(), ['extension', 'api-extension']);
    }

    /**
     * Determine whether the user can update the petition.
     *
     * @param  \App\ $user
     * @param  \App\Petition  $petition
     * @return mixed
     */
    public function update($user, Petition $petition)
    {
      $isExtension = in_array( auth()->getDefaultDriver(), ['extension', 'api-extension']);
      $isOwner     = $user->id == $petition->extension_id;
      return ($isExtension && $isOwner);
    }

    /**
     * Determine whether the user can delete the petition.
     *
     * @param  \App\ $user
     * @param  \App\Petition  $petition
     * @return mixed
     */
    public function delete($user, Petition $petition)
    {
      $isExtension = in_array( auth()->getDefaultDriver(), ['extension', 'api-extension']);
      $isOwner     = $user->id == $petition->extension_id;
      return ($isExtension && $isOwner);
    }

    /**
     * Determine whether the user can restore the petition.
     *
     * @param  \App\ $user
     * @param  \App\Petition  $petition
     * @return mixed
     */
    public function restore($user, Petition $petition)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the petition.
     *
     * @param  \App\ $user
     * @param  \App\Petition  $petition
     * @return mixed
     */
    public function forceDelete($user, Petition $petition)
    {
        //
    }
    
    public function respondPetition($user, Petition $petition){
      $isAdmin = in_array( auth()->getDefaultDriver(), ['admin', 'api-admin']);
      $isOwner = $user->petitions()->find( $petition->id )->get();
      return ($isAdmin && $isOwner);
    }
}

<?php

namespace App\Policies;

use App\User;
use App\Bill;
use Illuminate\Auth\Access\HandlesAuthorization;

class BillPolicy
{
    use HandlesAuthorization;

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
     * Determine whether the user can view the extension.
     *
     * @param  \App\User  $user
     * @param  \App\Bill  $bill
     * @return mixed
     */
    
    public function view($user, Bill $bill)
    {
      return $user->bills()->find( $bill->id );
    }

    /**
     * Determine whether the user can update the extension.
     *
     * @param  \App\User  $user
     * @param  \App\Bill  $bill
     * @return mixed
     */
    public function update($user, Bill $bill)
    {
      return $user->bills()->find( $bill->id );
    }

    /**
     * Determine whether the user can delete the extension.
     *
     * @param  \App\User  $user
     * @param  \App\Bill  $bill
     * @return mixed
     */
    public function delete($user, Bill $bill)
    {
      $isAdmin = in_array( auth()->getDefaultDriver(), ['admin', 'api-admin'] );
      $isOwner = $bill->admin_id == $user->id;
      return ($isAdmin && $isOwner);
    }
}

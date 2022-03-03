<?php

namespace App\Policies;

use App\User;
use App\Reminder;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReminderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create extensions.
     *
     * @param  \App\User  $user
     * @return mixed
     */

    public function viewAny(){
      return true;
    }

    public function create($user)
    {
      return in_array( auth()->getDefaultDriver(), ['admin', 'api-admin'] );
    }
    
    /**
     * Determine whether the user can view the extension.
     *
     * @param  \App\User  $user
     * @param  \App\Reminder  $reminder
     * @return mixed
     */
    
    public function view($user, Reminder $reminder)
    {
      return $user->posts()->find( $reminder->id );
    }

    /**
     * Determine whether the user can update the extension.
     *
     * @param  \App\User  $user
     * @param  \App\Reminder  $reminder
     * @return mixed
     */
    public function update($user, Reminder $reminder)
    {
      $isAdmin = in_array( auth()->getDefaultDriver(), ['admin', 'api-admin'] );
      $isOwner = $reminder->admin_id == $user->id;
      return ($isAdmin && $isOwner);
    }

    /**
     * Determine whether the user can delete the extension.
     *
     * @param  \App\User  $user
     * @param  \App\Reminder  $reminder
     * @return mixed
     */
    public function delete($user, Reminder $reminder)
    {
      $isAdmin = in_array( auth()->getDefaultDriver(), ['admin', 'api-admin'] );
      $isOwner = $reminder->admin_id == $user->id;
      return ($isAdmin && $isOwner);
    }
}

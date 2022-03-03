<?php

namespace App\Policies;

use App\User;
use App\Post;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
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
    
    public function index($user)
    {
      return in_array( auth()->getDefaultDriver(), ['admin', 'api-admin', 'extension', 'api-extension'] );
    }
    
    /**
     * Determine whether the user can view the extension.
     *
     * @param  \App\User  $user
     * @param  \App\Post  $post
     * @return mixed
     */
    
    public function view($user, Post $post)
    {
      return $user->posts()->find( $post->id );
    }

    /**
     * Determine whether the user can update the extension.
     *
     * @param  \App\User  $user
     * @param  \App\Post  $post
     * @return mixed
     */
    public function update($user, Post $post)
    {
      $isAdmin = in_array( auth()->getDefaultDriver(), ['admin', 'api-admin'] );
      $isOwner = $post->admin_id == $user->id;
      return ($isAdmin && $isOwner);
    }

    /**
     * Determine whether the user can delete the extension.
     *
     * @param  \App\User  $user
     * @param  \App\Post  $post
     * @return mixed
     */
    public function delete($user, Post $post)
    {
      $isAdmin = in_array( auth()->getDefaultDriver(), ['admin', 'api-admin'] );
      $isOwner = $post->admin_id == $user->id;
      return ($isAdmin && $isOwner);
    }
}

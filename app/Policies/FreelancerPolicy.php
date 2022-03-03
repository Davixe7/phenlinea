<?php

namespace App\Policies;

use App\User;
use App\Freelancer;
use Illuminate\Auth\Access\HandlesAuthorization;

class FreelancerPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the freelancer.
     *
     * @param  \App\User  $user
     * @param  \App\Freelancer  $freelancer
     * @return mixed
     */
     
    public function before($user){
      if( auth()->guard('web')->check() ){
        return true;
      }
      return false;
    }
    
    public function index(){
      return true;
    }
    
    public function view(User $user, Freelancer $freelancer)
    {
        //
    }

    /**
     * Determine whether the user can create freelancers.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the freelancer.
     *
     * @param  \App\User  $user
     * @param  \App\Freelancer  $freelancer
     * @return mixed
     */
    public function update(User $user, Freelancer $freelancer)
    {
        //
    }

    /**
     * Determine whether the user can delete the freelancer.
     *
     * @param  \App\User  $user
     * @param  \App\Freelancer  $freelancer
     * @return mixed
     */
    public function delete(User $user, Freelancer $freelancer)
    {
        //
    }

    /**
     * Determine whether the user can restore the freelancer.
     *
     * @param  \App\User  $user
     * @param  \App\Freelancer  $freelancer
     * @return mixed
     */
    public function restore(User $user, Freelancer $freelancer)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the freelancer.
     *
     * @param  \App\User  $user
     * @param  \App\Freelancer  $freelancer
     * @return mixed
     */
    public function forceDelete(User $user, Freelancer $freelancer)
    {
        //
    }
}

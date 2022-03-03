<?php

namespace App\Policies;

use App\User;
use App\Slide;
use Illuminate\Auth\Access\HandlesAuthorization;

class SlidePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the slide.
     *
     * @param  \App\User  $user
     * @param  \App\Slide  $slide
     * @return mixed
     */
     
    public function before(){
      return true;
    }
    public function view(User $user, Slide $slide)
    {
        //
    }

    /**
     * Determine whether the user can create slides.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the slide.
     *
     * @param  \App\User  $user
     * @param  \App\Slide  $slide
     * @return mixed
     */
    public function update(User $user, Slide $slide)
    {
        //
    }

    /**
     * Determine whether the user can delete the slide.
     *
     * @param  \App\User  $user
     * @param  \App\Slide  $slide
     * @return mixed
     */
    public function delete(User $user, Slide $slide)
    {
        //
    }

    /**
     * Determine whether the user can restore the slide.
     *
     * @param  \App\User  $user
     * @param  \App\Slide  $slide
     * @return mixed
     */
    public function restore(User $user, Slide $slide)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the slide.
     *
     * @param  \App\User  $user
     * @param  \App\Slide  $slide
     * @return mixed
     */
    public function forceDelete(User $user, Slide $slide)
    {
        //
    }
}

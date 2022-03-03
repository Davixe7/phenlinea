<?php

namespace App\Policies;

use App\User;
use App\Store;
use Illuminate\Auth\Access\HandlesAuthorization;

class StorePolicy
{
    use HandlesAuthorization;
    
    public function before($user){
      if( auth()->getDefaultDriver() == 'web' ){
        return true;
      }
    }

    public function update($user, Store $store)
    {
      return $user->id == $store->id;
    }
}

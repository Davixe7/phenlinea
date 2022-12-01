<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\Admin as AdminResource;

class UserController extends Controller
{
  public function index(){
    $user = auth()->user();
    $user->isAdmin = 0;
    $driver = auth()->getDefaultDriver();
    switch( $driver ) {
      case 'web':
        $user->isAdmin = 1;
        return new UserResource( $user );
        break;
      case 'admin':
        return new AdminResource( $user );
        break;
      case 'porteria':
        return new PorteriaResource( $user );
        break;
      case 'extension':
        return $user;
        break;
    }
  }
}

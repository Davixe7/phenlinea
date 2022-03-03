<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\Admin as AdminResource;
use App\Http\Resources\Freelancer as FreelancerResource;
use App\Http\Resources\Extension as ExtensionResource;

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
      case 'freelancer':
        return new AdminResource( $user );
        break;
      case 'extension':
        return $user;
        break;
    }
  }
}

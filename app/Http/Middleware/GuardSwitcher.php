<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class GuardSwitcher
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if( $request->filled('api_token') ){
          $request->headers->set('Authorization', "Bearer " . $request['api_token']);
        }
        
        $guards = array_keys( config('auth.guards') );
        foreach( $guards as $guard ){
          if( Auth::guard( $guard )->check() ){
            config([ 'auth.defaults.guard' => $guard ]);
            return $next($request);
          }
        }
        return $next($request);
    }
}

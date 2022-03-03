<?php

namespace App\Http\Middleware;

use Closure;

class CheckSuspendedAccount
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
        $user = auth()->user();
        if( !empty($user->key) && $user->solvencia == 2 ){
          return redirect()->route('suspended');
        }
        return $next($request);
    }
}

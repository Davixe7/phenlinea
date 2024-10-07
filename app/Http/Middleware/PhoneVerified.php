<?php

namespace App\Http\Middleware;
use Illuminate\Contracts\Auth\MustVerifyEmail;

use Closure;

class PhoneVerified
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
        //if( auth()->user() instanceOf MustVerifyEmail &&
        //    auth()->user()->phone_verification) {
        //    return redirect()->route('confirmphone');
        //}
        return $next($request);
    }
}

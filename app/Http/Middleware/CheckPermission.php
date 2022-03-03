<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $module)
    {
        if( auth()->check() ){
            $admin = auth()->user()->admin_id
                    ? auth()->user()->admin
                    : auth()->user();
                
            if($admin &&  $admin->modules()->whereSlug($module)->first() ){
                return $next( $request );
            }
        }

        return redirect()->route('modules.disabled');
    }
}

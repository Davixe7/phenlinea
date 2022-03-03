<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        $this->app['tymon.jwt.parser']->setChain([
            new \Tymon\JWTAuth\Http\Parser\AuthHeaders,
            (new \Tymon\JWTAuth\Http\Parser\QueryString(config('jwt.decrypt_cookies')))->setKey('api_token'),
            new \Tymon\JWTAuth\Http\Parser\Cookies,
        ]);
    }
}

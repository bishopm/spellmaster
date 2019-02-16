<?php

namespace Bishopm\Spellmaster\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Console\Scheduling\Schedule;

class SpellmasterServiceProvider extends ServiceProvider
{
    protected $commands = [];

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        if (! $this->app->routesAreCached()) {
            require __DIR__.'/../Http/api.routes.php';
        }
        $this->publishes([__DIR__.'/../Assets' => public_path('vendor/bishopm'),], 'public');
        config(['jwt.ttl' => 525600]);
        config(['jwt.refresh_ttl' => 525600]);
        config(['auth.providers.users.model'=>'Bishopm\Spellmaster\Models\User']);
        config(['jwt.user' => 'Bishopm\Spellmaster\Models\User']);
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        AliasLoader::getInstance()->alias("JWTFactory", 'Tymon\JWTAuth\Facades\JWTFactory');
        AliasLoader::getInstance()->alias("JWTAuth", 'Tymon\JWTAuth\Facades\JWTAuth');
        $this->app['router']->aliasMiddleware('handlecors', 'Barryvdh\Cors\HandleCors');
        $this->app['router']->aliasMiddleware('jwt.auth', 'Tymon\JWTAuth\Middleware\GetUserFromToken');
    }

}

<?php

namespace Ajax\JwtRedis;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Ajax\JwtRedis\JwtRedis;
use Ajax\JwtRedis\Middleware\JwtRedisMiddleware;

class JwtRedisServiceProvider extends ServiceProvider {

    public function boot()
    {
        $this->publishConfigFile();
        $router = $this->app->make(Router::class);
        $router->pushMiddlewareToGroup(config('jwt_redis.group', 'api'), JwtRedisMiddleware::class);
    }
    
    public function register()
    {
        //Bind Facades
        $this->app->bind('JwtRedis', function($app) {
            return new JwtRedis();
        });
    }

    /**
     * @return void
     */
    private function publishConfigFile()
    {
        $this->publishes([
            __DIR__.'/../config/config.php' => config_path('jwt_redis.php'),
        ]);
    }
}
<?php

namespace Ajax\JwtRedis\Middleware;

use Closure;
use Ajax\JwtRedis\Facades\JwtRedis;
use Illuminate\Support\Facades\Auth;
use Ajax\JwtRedis\Exceptions\JwtRedisException;

class JwtRedisMiddleware
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
        $response = $next($request);

        try {
            $excludedRoutes = config('jwt_redis.route_except');
            if($excludedRoutes){
                foreach ($excludedRoutes as $excludedRoute) {
                    if ($request->path() === $excludedRoute) {
                        return $response;
                    }
                }
            }
        
            //Get token from header
            $token = $request->bearerToken();
            if($token){
                $payload = Auth::payload();
                $params = $payload->get(config('jwt_redis.key_payload_hash'));
                
                //Check if token exists in redis
                if(JwtRedis::check($token, $params)){
                    return $response;
                }
            }

            throw new JwtRedisException();
        
        }catch (\Exception $e) {
            throw new JwtRedisException();
        }
        
    }
}
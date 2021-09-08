<?php

namespace Ajax\JwtRedis\Middleware;

use Closure;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Ajax\JwtRedis\Facades\JwtRedis;

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
            foreach (config('jwt_redis.route_except') as $excluded_route) {
                if ($request->path() === $excluded_route) {
                    return $response;
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

            return response()->json([
                'error' => 'API request Invalid.',
            ], Response::HTTP_UNAUTHORIZED);
        
        }catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], Response::HTTP_UNAUTHORIZED);
        }
        
    }
}
<?php 

namespace Ajax\JwtRedis;

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Config;

class JwtRedis{

    public function set(string $token, array $params):array
    {
        // encode session_id
        $session_id = $this->hashKey($params);

        //Push token in redis
        Redis::lpush($session_id, $token);

        //Limit list length in redis
        Redis::ltrim($session_id, 0, config('jwt_redis.limit_token', 5));

        return $this->getTokenBySessionIdFromRedis($session_id);
    }

    public function get(array $params):array
    {
        $session_id = $this->hashKey($params);
        return $this->getTokenBySessionIdFromRedis($session_id);
    }

    public function check(string $token, array $params):bool
    {
        $session_id = $this->hashKey($params);
        $redis = $this->getTokenBySessionIdFromRedis($session_id);

        if(count($redis) > 0 && in_array($token, $redis)){
            return true;
        }

        return false;
    }

    public function getTokenBySessionIdFromRedis($session_id):array
    {
        return Redis::lrange($session_id, 0, -1);
    }

    public function hashKey(array $params):string
    {
        return md5(implode('.', $params));
    }

}
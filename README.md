
## Install
```php
composer require quangdung93/jwt-redis
```

## Publish config
```php
php artisan vendor:publish --provider="Ajax\JwtRedis\JwtRedisServiceProvider"
```

## Config

`config/jwt_redis.php`

```php

//Limit the number of tokens stored in redis
'limit_token' => 5,

//The keys in the payload are used to hash session_id
'key_payload_hash' => [ 
    'imei',
    'user_id'
],

// Except route affected by middleware
'route_except' => [
    'api/login'
]

```


## Use

```php
// use Ajax\JwtRedis\Facades\JwtRedis;

$imei = '123';
$user_id = '1';

$params = [$imei, $user_id];

//$params is array include key payload used to hash to session_id

// Save token to redis
JwtRedis::set($token, $params)

//Get token
JwtRedis::get($params)

//Check token exists in Redis
JwtRedis::check($token, $params)
```

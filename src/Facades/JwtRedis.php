<?php

namespace Ajax\JwtRedis\Facades;

use Illuminate\Support\Facades\Facade;

class JwtRedis extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'JwtRedis';
    }
}
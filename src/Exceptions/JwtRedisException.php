<?php

namespace Ajax\JwtRedis\Exceptions;

use Exception;

class JwtRedisException extends Exception
{
    protected $message = 'API request Invalid';
    protected $code = 401;
}
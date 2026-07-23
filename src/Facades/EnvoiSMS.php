<?php

namespace EnvoiSMS\Laravel\Facades;

use EnvoiSMS\Client;
use Illuminate\Support\Facades\Facade;

/**
 * @method static array send(array $payload)
 * @method static array sendBulk(array $payload)
 * @method static array sendOtp(array $payload)
 * @method static array checkOtp(string $sessionId, string $code)
 * @method static array analytics(int $days = 30)
 * @method static array listMessages(int $limit = 50)
 *
 * @see \EnvoiSMS\Client
 */
class EnvoiSMS extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Client::class;
    }
}

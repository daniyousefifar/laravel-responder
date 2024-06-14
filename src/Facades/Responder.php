<?php

namespace Zirsakht\Responder\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method self setStatus(string $status)
 * @method self setCode(integer $code)
 * @method self setMessage(string $message)
 * @method self setErrors(array $errors)
 * @method self setError(string $code, string $message)
 * @method self setHeaders(array $headers)
 * @method self setHeader(string $key, mixed $value)
 * @method self setPayload(mixed $payload)
 *
 * @method self success()
 * @method self fail()
 * @method self error()
 *
 * @method mixed xml()
 * @method mixed json()
 * @method mixed preferred()
 */
class Responder extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'responder';
    }
}

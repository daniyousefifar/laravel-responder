<?php

namespace Zirsakht\Responder\Macros;

use Closure;
use Illuminate\Container\Container;
use Illuminate\Routing\ResponseFactory;
use Zirsakht\Responder\Classes\ArrayToXML;

class ResponseMacros
{
    public function xml(): Closure
    {
        return function ($data = [], int $status = 200, array $headers = []) {
            if (is_array($data)) {
                $xml = ArrayToXML::convert($data, 'root');
            } elseif (is_object($data) && method_exists($data, 'toArray')) {
                $xml = ArrayToXML::convert($data, 'root');
            } elseif (is_string($data)) {
                $xml = $data;
            } else {
                $xml = '';
            }

            if (!isset($headers['Content-Type'])) {
                $headers = array_merge($headers, ['Content-Type' => 'application/xml']);
            }

            return ResponseFactory::make($xml, $status, $headers);
        };
    }

    public function preferred(): Closure
    {
        return function ($data = [], int $status = 200, array $headers = []) {
            $request = Container::getInstance()->make('request');
            $response = app(ResponseFactory::class);

            if ($request->wantsJson()) {
                return $response->json(
                    $data,
                    $status,
                    array_merge($headers, [
                        'Content-Type' => 'application/json'
                    ]),
                );
            }

            return $response->xml(
                $data,
                $status,
                array_merge($headers, [
                    'Content-Type' => 'application/xml',
                ]),
            );
        };
    }
}

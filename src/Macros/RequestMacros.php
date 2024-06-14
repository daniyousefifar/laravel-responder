<?php

namespace Zirsakht\Responder\Macros;

use Closure;
use Illuminate\Support\Str;
use Zirsakht\Responder\Classes\XMLToArray;
use Zirsakht\Responder\Exceptions\CouldNotParseXML;

class RequestMacros
{
    public function isXML(): Closure
    {
        return function () {
            return Str::contains($this->header('CONTENT_TYPE') ?? '', ['/xml', '+xml']);
        };
    }

    public function wantsXML(): Closure
    {
        return function () {
            $acceptable = $this->getAcceptableContentTypes();

            return isset($acceptable[0]) && Str::contains(strtolower($acceptable[0]), ['/xml', '+xml']);
        };
    }

    public function xml(): Closure
    {
        return function () {
            if (!$this->isXML()) {
                return [];
            }

            try {
                return XMLToArray::convert($this->getContent());
            } catch (\Exception $exception) {
                throw CouldNotParseXML::payload($this->getContent());
            }
        };
    }
}

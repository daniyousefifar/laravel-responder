<?php

namespace Zirsakht\Responder\Exceptions;

use Exception;

class CouldNotParseXML extends Exception
{
    public static function payload($xml): static
    {
        return new static($xml);
    }
}

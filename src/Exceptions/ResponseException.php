<?php

namespace Jdgrieco\LoggiPHP\Exceptions;

use Throwable;

class ResponseException extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
<?php

namespace Exceptions;

use Nebucord\Exceptions\Throwable;

class RateLimitExceededException extends \Exception
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

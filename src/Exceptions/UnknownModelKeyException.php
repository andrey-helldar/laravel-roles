<?php

namespace Helldar\Roles\Exceptions;

class UnknownModelKeyException extends \Exception
{
    public function __construct(string $value)
    {
        $message = \printf('Unknown model key: "%s"', $value);

        parent::__construct($message, 500);
    }
}

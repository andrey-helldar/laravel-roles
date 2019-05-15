<?php

namespace Helldar\Roles\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class UnknownModelKeyException extends HttpException
{
    public function __construct(string $value)
    {
        $message = \printf('Unknown model key: "%s"', $value);
        $code    = 500;

        parent::__construct($code, $message, null, [], $code);
    }
}

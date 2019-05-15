<?php

namespace Helldar\Roles\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class RoleNotFoundException extends HttpException
{
    public function __construct(string $role)
    {
        $message = \printf('Role "%s" not found!', $role);
        $code    = 404;

        parent::__construct($code, $message, null, [], $code);
    }
}

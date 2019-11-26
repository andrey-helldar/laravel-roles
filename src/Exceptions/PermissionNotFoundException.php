<?php

namespace Helldar\Roles\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

use function printf;

class PermissionNotFoundException extends HttpException
{
    public function __construct(string $role)
    {
        $message = printf('Permission "%s" not found!', $role);
        $code    = 404;

        parent::__construct($code, $message, null, [], $code);
    }
}

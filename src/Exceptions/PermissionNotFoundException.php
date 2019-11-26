<?php

namespace Helldar\Roles\Exceptions;

use function printf;

use Symfony\Component\HttpKernel\Exception\HttpException;

class PermissionNotFoundException extends HttpException
{
    public function __construct(string $role)
    {
        $message = printf('Permission "%s" not found!', $role);
        $code    = 404;

        parent::__construct($code, $message, null, [], $code);
    }
}

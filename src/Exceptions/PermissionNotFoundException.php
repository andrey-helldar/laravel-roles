<?php

namespace Helldar\Roles\Exceptions;

class PermissionNotFoundException extends \Exception
{
    public function __construct(string $role)
    {
        $message = \printf('Permission "%s" not found!', $role);

        parent::__construct($message, 404);
    }
}

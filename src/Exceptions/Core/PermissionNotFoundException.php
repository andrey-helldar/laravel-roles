<?php

namespace Helldar\Roles\Exceptions\Core;

use Exception;

class PermissionNotFoundException extends Exception
{
    public function __construct(string $role)
    {
        $message = printf('Permission "%s" not found!', $role);

        parent::__construct($message, 500);
    }
}

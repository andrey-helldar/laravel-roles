<?php

namespace Helldar\Roles\Exceptions;

class RoleNotFoundException extends \Exception
{
    public function __construct(string $role)
    {
        $message = \printf('Role "%s" not found!', $role);

        parent::__construct($message, 404);
    }
}

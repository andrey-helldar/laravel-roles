<?php

namespace Helldar\Roles\Exceptions;

class RoleAccessIsDeniedException extends \Exception
{
    public function __construct()
    {
        $message = 'User does not have permission to view this content. Access is denied.';

        parent::__construct($message, 403);
    }
}

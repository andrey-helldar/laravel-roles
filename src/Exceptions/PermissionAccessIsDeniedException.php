<?php

namespace Helldar\Roles\Exceptions;

class PermissionAccessIsDeniedException extends \Exception
{
    public function __construct()
    {
        $message = 'User does not have permission to view this content. Access is denied.';

        parent::__construct($message, 403);
    }
}

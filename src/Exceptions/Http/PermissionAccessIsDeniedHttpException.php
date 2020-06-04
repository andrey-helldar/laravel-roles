<?php

namespace Helldar\Roles\Exceptions\Http;

use Symfony\Component\HttpKernel\Exception\HttpException;

class PermissionAccessIsDeniedHttpException extends HttpException
{
    public function __construct()
    {
        $message = 'User does not have permission to view this content. Access is denied.';
        $code    = 403;

        parent::__construct($code, $message, null, [], $code);
    }
}

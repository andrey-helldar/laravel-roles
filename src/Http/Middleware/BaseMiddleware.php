<?php

namespace Helldar\Roles\Http\Middleware;

use Helldar\Roles\Traits\RootAccess;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

abstract class BaseMiddleware
{
    use RootAccess;

    protected function check(): void
    {
        if (Auth::guest()) {
            throw new AccessDeniedHttpException('User is not authorized', null, 403);
        }
    }
}

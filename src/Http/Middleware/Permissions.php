<?php

namespace Helldar\Roles\Http\Middleware;

use Closure;
use Helldar\Roles\Exceptions\Http\PermissionAccessIsDeniedHttpException;

class Permissions extends BaseMiddleware
{
    /**
     * Checks the entry of all of the specified permissions.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Closure  $next
     * @param  string  ...$permissions
     *
     * @throws \Helldar\Roles\Exceptions\Http\PermissionAccessIsDeniedHttpException
     *
     * @return mixed
     */
    public function handle($request, Closure $next, ...$permissions)
    {
        $this->check();

        if ($this->hasRoot($request) || $request->user()->hasPermissions($permissions)) {
            return $next($request);
        }

        throw new PermissionAccessIsDeniedHttpException();
    }
}

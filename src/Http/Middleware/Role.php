<?php

namespace Helldar\Roles\Http\Middleware;

use Closure;
use Helldar\Roles\Exceptions\Http\RoleAccessIsDeniedHttpException;

class Role extends BaseMiddleware
{
    /**
     * Checks for the occurrence of one of the specified roles.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Closure  $next
     * @param  string  ...$roles
     *
     * @throws \Helldar\Roles\Exceptions\Http\RoleAccessIsDeniedHttpException
     *
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        $this->check();

        if ($this->hasRoot($request) || $request->user()->hasRole($roles)) {
            return $next($request);
        }

        throw new RoleAccessIsDeniedHttpException();
    }
}

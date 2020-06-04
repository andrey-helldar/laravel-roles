<?php

namespace Helldar\Roles\Http\Middleware;

use Closure;
use Helldar\Roles\Exceptions\Http\RoleAccessIsDeniedHttpException;

class Roles extends BaseMiddleware
{
    /**
     * Checks the entry of all of the specified roles.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles
     *
     * @throws \Helldar\Roles\Exceptions\Http\RoleAccessIsDeniedHttpException
     *
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        $this->check();

        if ($this->hasRoot($request) || $request->user()->hasRoles($roles)) {
            return $next($request);
        }

        throw new RoleAccessIsDeniedHttpException();
    }
}

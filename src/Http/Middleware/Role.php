<?php

namespace Helldar\Roles\Http\Middleware;

use Closure;
use Helldar\Roles\Exceptions\RoleAccessIsDeniedException;

class Role
{
    /**
     * Checks for the occurrence of one of the specified roles.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string ...$roles
     *
     * @throws \Helldar\Roles\Exceptions\RoleAccessIsDeniedException
     *
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        foreach ($roles as $role) {
            if ($request->user()->hasRole($role)) {
                return $next($request);
            }
        }

        throw new RoleAccessIsDeniedException;
    }
}

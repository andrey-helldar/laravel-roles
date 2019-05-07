<?php

namespace Helldar\Roles\Http\Middleware;

use Closure;
use Helldar\Roles\Exceptions\RoleAccessIsDeniedException;

class Roles
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string ...$roles
     *
     * @return mixed
     *
     * @throws \Helldar\Roles\Exceptions\RoleAccessIsDeniedException
     *
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

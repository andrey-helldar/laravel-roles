<?php

namespace Helldar\Roles\Http\Middleware;

use Closure;
use Helldar\Roles\Exceptions\PermissionAccessIsDeniedException;

class Permissions
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string ...$permissions
     *
     * @return mixed
     *
     * @throws \Helldar\Roles\Exceptions\PermissionAccessIsDeniedException
     *
     */
    public function handle($request, Closure $next, ...$permissions)
    {
        foreach ($permissions as $permission) {
            if ($request->user()->hasPermission($permission)) {
                return $next($request);
            }
        }

        throw new PermissionAccessIsDeniedException;
    }
}

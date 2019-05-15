<?php

namespace Helldar\Roles\Http\Middleware;

use Closure;
use Helldar\Roles\Exceptions\PermissionAccessIsDeniedException;
use Helldar\Roles\Traits\RootAccess;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class Permissions
{
    use RootAccess;

    /**
     * Checks the entry of all of the specified permissions.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string ...$permissions
     *
     * @throws \Helldar\Roles\Exceptions\PermissionAccessIsDeniedException
     *
     * @return mixed
     */
    public function handle($request, Closure $next, ...$permissions)
    {
        if (!Auth::check()) {
            throw new AccessDeniedHttpException('User is not authorized', null, 403);
        }

        if ($this->isRoot($request)) {
            return $next($request);
        }

        foreach ($permissions as $permission) {
            if (!$request->user()->hasPermission($permission)) {
                throw new PermissionAccessIsDeniedException;
            }
        }

        return $next($request);
    }
}

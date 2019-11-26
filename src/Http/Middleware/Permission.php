<?php

namespace Helldar\Roles\Http\Middleware;

use Closure;
use Helldar\Roles\Exceptions\PermissionAccessIsDeniedException;
use Helldar\Roles\Traits\RootAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class Permission
{
    use RootAccess;

    /**
     * Checks for the entry of one of the specified permissions.
     *
     * @param Request $request
     * @param Closure $next
     * @param string ...$permissions
     *
     * @throws PermissionAccessIsDeniedException
     *
     * @return mixed
     */
    public function handle($request, Closure $next, ...$permissions)
    {
        if (! Auth::check()) {
            throw new AccessDeniedHttpException('User is not authorized', null, 403);
        }

        if ($this->isRoot($request)) {
            return $next($request);
        }

        foreach ($permissions as $permission) {
            if ($request->user()->hasPermission($permission)) {
                return $next($request);
            }
        }

        throw new PermissionAccessIsDeniedException;
    }
}

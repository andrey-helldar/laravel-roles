<?php

namespace Helldar\Roles\Http\Middleware;

use Closure;
use Helldar\Roles\Exceptions\RoleAccessIsDeniedException;
use Helldar\Roles\Traits\RootAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class Role
{
    use RootAccess;

    /**
     * Checks for the occurrence of one of the specified roles.
     *
     * @param Request $request
     * @param Closure $next
     * @param string ...$roles
     *
     * @throws RoleAccessIsDeniedException
     *
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            throw new AccessDeniedHttpException('User is not authorized', null, 403);
        }

        if ($this->isRoot($request)) {
            return $next($request);
        }

        if ($request->user()->hasRole($roles)) {
            return $next($request);
        }

        throw new RoleAccessIsDeniedException;
    }
}

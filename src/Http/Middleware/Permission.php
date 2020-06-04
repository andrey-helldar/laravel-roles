<?php

namespace Helldar\Roles\Http\Middleware;

use Closure;
use Helldar\Roles\Exceptions\Http\PermissionAccessIsDeniedHttpException;

class Permission extends BaseMiddleware
{
    /**
     * Checks for the entry of one of the specified permissions.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$permissions
     *
     * @throws \Helldar\Roles\Exceptions\Http\PermissionAccessIsDeniedHttpException
     *
     * @return mixed
     */
    public function handle($request, Closure $next, ...$permissions)
    {
        $this->check();

        if ($this->hasRoot($request) || $this->has($request, $permissions)) {
            return $next($request);
        }

        throw new PermissionAccessIsDeniedHttpException();
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $permissions
     *
     * @return bool
     */
    protected function has($request, array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($request->user()->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }
}

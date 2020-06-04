<?php

namespace Helldar\Roles\Traits;

use Helldar\Roles\Facades\Config;

trait RootAccess
{
    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return bool
     */
    protected function hasRoot($request): bool
    {
        if ($roles = $this->getRootRoles()) {
            /** @var \Helldar\Roles\Traits\HasRoles|\Illuminate\Contracts\Auth\Authenticatable $user */
            $user = $request->user();

            return $user->hasRole($roles) || $user->hasRootRole();
        }

        return false;
    }

    protected function getRootRoles()
    {
        return Config::rootRoles();
    }
}

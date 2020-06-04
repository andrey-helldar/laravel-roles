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
            /** @var \Illuminate\Contracts\Auth\Authenticatable|\Helldar\Roles\Traits\HasRoles $user */
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

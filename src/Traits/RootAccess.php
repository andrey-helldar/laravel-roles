<?php

namespace Helldar\Roles\Traits;

use Helldar\Roles\Helpers\Config;

trait RootAccess
{
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return bool
     */
    private function isRoot($request): bool
    {
        $roles = Config::get('root_roles', false);

        return $roles == false ? false : $request->user()->hasRole($roles);
    }
}

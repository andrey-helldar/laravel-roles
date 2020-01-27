<?php

namespace Helldar\Roles\Traits;

use Helldar\Roles\Helpers\Config;
use Illuminate\Http\Request;

trait RootAccess
{
    /**
     * @param Request $request
     *
     * @return bool
     */
    protected function isRoot($request): bool
    {
        $roles = Config::get('root_roles', false);

        return $roles == false ? false : $request->user()->hasRole($roles);
    }
}

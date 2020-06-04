<?php

namespace Helldar\Roles\Traits;

trait RootAccess
{
    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return bool
     */
    protected function hasRoot($request): bool
    {
        return $request->user()->hasRootRole();
    }
}

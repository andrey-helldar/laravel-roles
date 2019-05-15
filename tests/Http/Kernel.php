<?php

namespace Tests\Http;

use Helldar\Roles\Http\Middleware\Permission;
use Helldar\Roles\Http\Middleware\Permissions;
use Helldar\Roles\Http\Middleware\Role;
use Helldar\Roles\Http\Middleware\Roles;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $routeMiddleware = [
        'role'        => Role::class,
        'roles'       => Roles::class,
        'permission'  => Permission::class,
        'permissions' => Permissions::class,
    ];
}

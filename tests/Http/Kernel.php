<?php

namespace Tests\Http;

use Helldar\Roles\Http\Middleware\Permissions;
use Helldar\Roles\Http\Middleware\Roles;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $routeMiddleware = [
        'roles'       => Roles::class,
        'permissions' => Permissions::class,
    ];
}

<?php

namespace Helldar\Roles\Facades;

use Helldar\Roles\Support\Config as ConfigSupport;
use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed get($key, $default = null)
 * @method static void set($key, $value)
 * @method static string name()
 * @method static string connection()
 * @method static bool useBlade()
 * @method static bool useCanDirective()
 * @method static bool useCache()
 * @method static int cacheTtl()
 * @method static string filename()
 * @method static string|null defaultRole()
 */
class Config extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ConfigSupport::class;
    }
}

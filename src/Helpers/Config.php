<?php

namespace Helldar\Roles\Helpers;

use Illuminate\Support\Facades\Config as IlluminateConfig;

class Config
{
    public static function get($key, $default = null)
    {
        return IlluminateConfig::get('laravel_roles.' . $key, $default);
    }
}

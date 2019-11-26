<?php

namespace Helldar\Roles\Traits;

use Illuminate\Support\Str;

use function trim;

trait SetAttribute
{
    protected function setNameAttribute($value)
    {
        $value = Str::slug(trim($value), '_');

        $this->setManual('name', $value);
    }

    private function setManual($key, $value, $default = null)
    {
        $this->attributes[$key] = $value ?: $default;
    }
}

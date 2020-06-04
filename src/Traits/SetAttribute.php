<?php

namespace Helldar\Roles\Traits;

use Illuminate\Support\Str;

trait SetAttribute
{
    protected function setNameAttribute($value)
    {
        $value = Str::slug(trim($value), '_');

        $this->setManual('name', $value);
    }

    protected function setManual($key, $value)
    {
        $this->attributes[$key] = $value;
    }
}

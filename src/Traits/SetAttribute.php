<?php

namespace Helldar\Roles\Traits;

use Illuminate\Support\Str;

/** @mixin \Illuminate\Database\Eloquent\Model */
trait SetAttribute
{
    protected function setSlugAttribute($value)
    {
        $value = Str::slug(trim($value), '_');

        $this->setManual('slug', $value);
    }

    protected function setTitleAttribute($value)
    {
        $this->setManual('title', trim($value));
    }

    protected function setManual($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    protected function getTitleAttribute($value): string
    {
        return $value ?: Str::title($this->slug);
    }
}

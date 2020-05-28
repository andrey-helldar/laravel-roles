<?php

namespace Helldar\Roles\Traits;

use Closure;
use Helldar\Roles\Helpers\Config;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/** @mixin \Illuminate\Database\Eloquent\Model */
trait Cacheable
{
    protected function cache(string $prefix, Closure $callback, $values)
    {
        if ($this->allowCache()) {
            $key = $this->cacheKey($prefix, $values);
            $ttl = $this->cacheTtl();

            return Cache::remember($key, $ttl, $callback);
        }

        return $callback();
    }

    protected function cacheKey(string $prefix, $values): string
    {
        $values = Arr::flatten(Arr::wrap($values));

        return Str::slug(
            $prefix . '-' . $this->cacheUserKey() . '-' . implode('-', $values)
        );
    }

    protected function cacheUserKey(): string
    {
        return $this->getAttribute(
            $this->getKeyName()
        );
    }

    protected function cacheTtl(): int
    {
        return (int) Config::get('cache_ttl', 300);
    }

    protected function allowCache(): bool
    {
        return (bool) Config::get('use_cache', false);
    }
}

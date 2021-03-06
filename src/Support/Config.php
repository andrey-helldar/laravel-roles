<?php

namespace Helldar\Roles\Support;

use Illuminate\Support\Facades\Config as IlluminateConfig;

class Config
{
    public const NAME = 'roles';

    public function get($key, $default = null)
    {
        return IlluminateConfig::get($this->compileKey($key), $default);
    }

    public function set($key, $value): void
    {
        IlluminateConfig::set($this->compileKey($key), $value);
    }

    public function name(): string
    {
        return static::NAME;
    }

    public function connection(): string
    {
        return $this->get('connection', 'mysql');
    }

    public function useBlade(): bool
    {
        return (bool) $this->get('use_blade', false);
    }

    public function useCanDirective(): bool
    {
        return (bool) $this->get('use_can_directive', false);
    }

    public function useCache(): bool
    {
        return (bool) $this->get('use_cache', false);
    }

    public function cacheTtl(): int
    {
        return (int) $this->get('cache_ttl', 3600);
    }

    public function filename()
    {
        return $this->name() . '.php';
    }

    public function defaultRole(): ?string
    {
        return $this->get('default_role');
    }

    protected function compileKey($key): string
    {
        return $this->name() . '.' . $key;
    }
}

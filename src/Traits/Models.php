<?php

namespace Helldar\Roles\Traits;

use Helldar\Roles\Exceptions\UnknownModelKeyException;
use Helldar\Roles\Helpers\Config;

trait Models
{
    /**
     * @param string $key
     *
     * @throws \Helldar\Roles\Exceptions\UnknownModelKeyException
     *
     * @return \Helldar\Roles\Models\Role|\Helldar\Roles\Models\Permission|\Illuminate\Database\Eloquent\Model
     */
    private function model(string $key)
    {
        $models = Config::get('models', []);

        if (\array_key_exists($key, $models)) {
            return $models[$key];
        }

        throw new UnknownModelKeyException($key);
    }
}

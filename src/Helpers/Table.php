<?php

namespace Helldar\Roles\Helpers;

use Illuminate\Support\Facades\Config;

class Table
{
    public static function connection(): string
    {
        return Config::get('laravel_roles.connection', 'mysql');
    }

    public static function all(bool $with_users = false): array
    {
        $tables = Config::get('laravel_roles.tables', []);

        if ($with_users) {
            return $tables;
        }

        return \array_filter($tables, function ($value, $key) {
            return $key !== 'users';
        });
    }

    public static function name(string $key): string
    {
        return Config::get('laravel_roles.tables.' . $key, $key);
    }
}

<?php

namespace Helldar\Roles\Helpers;

class Table
{
    public static function connection(): string
    {
        return Config::get('connection', 'mysql');
    }

    public static function all(bool $with_users = false): array
    {
        $tables = Config::get('tables', []);

        if ($with_users) {
            return $tables;
        }

        return \array_filter($tables, function ($value, $key) {
            return $key !== 'users';
        });
    }

    public static function name(string $key): string
    {
        return Config::get('tables.' . $key, $key);
    }
}

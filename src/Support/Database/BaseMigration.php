<?php

namespace Helldar\Roles\Support\Database;

use Closure;
use Helldar\Roles\Facades\Config;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;
use Illuminate\Support\Facades\Schema;

abstract class BaseMigration extends Migration
{
    protected $permissions = 'permissions';

    protected $role_permission = 'role_permission';

    protected $role_permissions = 'role_permissions';

    protected $roles = 'roles';

    protected $user_permission = 'user_permission';

    protected $user_role = 'user_role';

    protected $user_roles = 'user_roles';

    protected $users = 'users';

    abstract public function up();

    abstract public function down();

    protected function schema(): Builder
    {
        return Schema::connection(
            Config::connection()
        );
    }

    protected function create(string $table, Closure $callback)
    {
        $this->schema()->create($table, $callback);
    }

    protected function drop(string $table)
    {
        $this->schema()->dropIfExists($table);
    }

    protected function table(string $table, Closure $callback)
    {
        $this->schema()->table($table, $callback);
    }

    protected function rename(string $from, string $to)
    {
        $this->schema()->rename($from, $to);
    }

    protected function dropTables(...$tables)
    {
        foreach ($tables as $table) {
            $this->drop($table);
        }
    }

    protected function createPivot(string $table, string $first_table, string $second_table, string $first_key, string $second_key)
    {
        $this->create($table, function (Blueprint $table) use ($first_table, $second_table, $first_key, $second_key) {
            $table->unsignedBigInteger($first_key);
            $table->unsignedBigInteger($second_key);

            $table->foreign($first_key)->references('id')->on($first_table)->onDelete('cascade');
            $table->foreign($second_key)->references('id')->on($second_table)->onDelete('cascade');

            $table->primary([$first_key, $second_key]);
        });
    }
}

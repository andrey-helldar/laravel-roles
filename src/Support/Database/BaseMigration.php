<?php

namespace Helldar\Roles\Support\Database;

use Closure;
use Helldar\Roles\Facades\Config;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Builder;
use Illuminate\Support\Facades\Schema;

abstract class BaseMigration extends Migration
{
    protected $roles = 'roles';

    protected $permissions = 'permissions';

    protected $user_roles = 'user_roles';

    protected $user_role = 'user_role';

    protected $role_permissions = 'role_permissions';

    protected $role_permission = 'role_permission';

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
}

<?php

use Helldar\Roles\Facades\Config;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Builder;
use Illuminate\Support\Facades\Schema;

class RenameRolesAndPermissionsPivotTables extends Migration
{
    public function up()
    {
        $this->schema()->rename('user_roles', 'user_role');
        $this->schema()->rename('role_permissions', 'role_permission');
    }

    public function down()
    {
        $this->schema()->rename('user_role', 'user_roles');
        $this->schema()->rename('role_permission', 'role_permissions');
    }

    protected function schema(): Builder
    {
        return Schema::connection(
            Config::connection()
        );
    }
}

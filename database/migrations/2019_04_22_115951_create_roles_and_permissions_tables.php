<?php

use Helldar\Roles\Support\Database\BaseMigration;
use Illuminate\Database\Schema\Blueprint;

class CreateRolesAndPermissionsTables extends BaseMigration
{
    public function up()
    {
        $this->createTable($this->roles);
        $this->createTable($this->permissions);

        $this->createPivot($this->user_roles, $this->users, $this->roles, 'user_id', 'role_id');
        $this->createPivot($this->role_permissions, $this->roles, $this->permissions, 'role_id', 'permission_id');
    }

    public function down()
    {
        $this->schema()->disableForeignKeyConstraints();

        $this->dropTables($this->role_permissions, $this->user_roles, $this->permissions, $this->roles);

        $this->schema()->enableForeignKeyConstraints();
    }

    protected function createTable(string $table)
    {
        $this->create($table, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->timestamps();
        });
    }
}

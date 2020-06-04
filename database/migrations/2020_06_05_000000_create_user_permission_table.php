<?php

use Helldar\Roles\Support\Database\BaseMigration;

class CreateUserPermissionTable extends BaseMigration
{
    public function up()
    {
        $this->createPivot($this->user_permission, $this->users, $this->permissions, 'user_id', 'permission_id');
    }

    public function down()
    {
        $this->schema()->disableForeignKeyConstraints();

        $this->dropTables($this->user_permission);

        $this->schema()->enableForeignKeyConstraints();
    }
}

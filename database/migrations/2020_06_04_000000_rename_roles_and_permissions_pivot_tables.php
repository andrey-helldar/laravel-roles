<?php

class RenameRolesAndPermissionsPivotTables extends BaseMigration
{
    public function up()
    {
        $this->schema()->rename($this->user_roles, $this->user_role);
        $this->schema()->rename($this->role_permissions, $this->role_permission);
    }

    public function down()
    {
        $this->schema()->rename($this->user_role, $this->user_roles);
        $this->schema()->rename($this->role_permission, $this->role_permissions);
    }
}

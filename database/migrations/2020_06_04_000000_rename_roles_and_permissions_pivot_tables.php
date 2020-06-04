<?php

class RenameRolesAndPermissionsPivotTables extends BaseMigration
{
    public function up()
    {
        $this->rename($this->user_roles, $this->user_role);
        $this->rename($this->role_permissions, $this->role_permission);
    }

    public function down()
    {
        $this->rename($this->user_role, $this->user_roles);
        $this->rename($this->role_permission, $this->role_permissions);
    }
}

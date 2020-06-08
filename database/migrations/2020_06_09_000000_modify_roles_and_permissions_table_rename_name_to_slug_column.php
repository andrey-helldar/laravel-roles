<?php

use Helldar\Roles\Support\Database\BaseMigration;
use Illuminate\Database\Schema\Blueprint;

class ModifyRolesAndPermissionsTableRenameNameToSlugColumn extends BaseMigration
{
    public function up()
    {
        $this->renameColumn($this->roles, 'name', 'slug');
        $this->renameColumn($this->permissions, 'name', 'slug');
    }

    public function down()
    {
        $this->renameColumn($this->roles, 'slug', 'name');
        $this->renameColumn($this->permissions, 'slug', 'name');
    }

    protected function renameColumn(string $table, string $from, string $to)
    {
        $this->table($table, function (Blueprint $table) use ($from, $to) {
            $table->renameColumn($from, $to);
        });
    }
}

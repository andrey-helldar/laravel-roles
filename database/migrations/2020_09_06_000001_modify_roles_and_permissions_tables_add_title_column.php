<?php

use Helldar\Roles\Support\Database\BaseMigration;
use Illuminate\Database\Schema\Blueprint;

class ModifyRolesAndPermissionsTablesAddTitleColumn extends BaseMigration
{
    public function up()
    {
        $this->addTitleColumn($this->roles);
        $this->addTitleColumn($this->permissions);
    }

    public function down()
    {
        $this->dropTitleColumn($this->roles);
        $this->dropTitleColumn($this->permissions);
    }

    protected function addTitleColumn(string $table): void
    {
        $this->table($table, function (Blueprint $table) {
            $table->string('title')->nullable()->after('slug');
        });
    }

    protected function dropTitleColumn(string $table): void
    {
        $this->table($table, function (Blueprint $table) {
            $table->dropColumn('title');
        });
    }
}

<?php

use Helldar\Roles\Support\Database\BaseMigration;
use Illuminate\Database\Schema\Blueprint;

class ModifyRolesTableAddIsRootColumn extends BaseMigration
{
    public function up()
    {
        $this->table($this->roles, function (Blueprint $table) {
            $table->boolean('is_root')->default(false)->after('name');
        });
    }

    public function down()
    {
        $this->table($this->roles, function (Blueprint $table) {
            $table->dropColumn('is_root');
        });
    }
}

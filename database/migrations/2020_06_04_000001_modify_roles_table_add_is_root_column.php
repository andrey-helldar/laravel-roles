<?php

use Illuminate\Database\Schema\Blueprint;

class ModifyRolesTableAddIsRootColumn extends BaseMigration
{
    public function up()
    {
        $this->schema()->table($this->roles, function (Blueprint $table) {
            $table->boolean('is_root')->default(false)->after('name');
        });
    }

    public function down()
    {
        $this->schema()->table($this->roles, function (Blueprint $table) {
            $table->dropColumn('is_root');
        });
    }
}

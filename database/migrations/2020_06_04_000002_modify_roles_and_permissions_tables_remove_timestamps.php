<?php

use Illuminate\Database\Schema\Blueprint;

class ModifyRolesAndPermissionsTablesRemoveTimestamps extends BaseMigration
{
    public function up()
    {
        $this->dropTimestamps($this->roles);
        $this->dropTimestamps($this->permissions);
    }

    public function down()
    {
        $this->createTimestamps($this->roles);
        $this->createTimestamps($this->permissions);
    }

    protected function dropTimestamps(string $table)
    {
        $this->schema()->table($table, function (Blueprint $table) {
            $table->dropTimestamps();
        });
    }

    protected function createTimestamps(string $table)
    {
        $this->schema()->table($table, function (Blueprint $table) {
            $table->timestamps();
        });
    }
}

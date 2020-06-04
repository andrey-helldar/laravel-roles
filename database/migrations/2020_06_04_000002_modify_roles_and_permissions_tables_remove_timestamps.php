<?php

use Illuminate\Database\Schema\Blueprint;

class ModifyRolesAndPermissionsTablesRemoveTimestamps extends BaseMigration
{
    public function up()
    {
        $this->drop($this->roles);
        $this->drop($this->permissions);
    }

    public function down()
    {
        $this->create($this->roles);
        $this->create($this->permissions);
    }

    protected function drop(string $table)
    {
        $this->schema()->table($table, function (Blueprint $table) {
            $table->dropTimestamps();
        });
    }

    protected function create(string $table)
    {
        $this->schema()->table($table, function (Blueprint $table) {
            $table->timestamps();
        });
    }
}

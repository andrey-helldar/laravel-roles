<?php

use Helldar\Roles\Support\Database\BaseMigration;
use Illuminate\Database\Schema\Blueprint;

class ModifyRolesAndPermissionsTableOptimizeIndexes extends BaseMigration
{
    public function up()
    {
        $this->optimizeIndexes($this->user_role, 'user_id');
        $this->optimizeIndexes($this->role_permission, 'role_id');
    }

    public function down()
    {
        $this->revertIndexes($this->user_role, 'user_id');
        $this->revertIndexes($this->role_permission, 'role_id');
    }

    protected function optimizeIndexes(string $table, string $first_key)
    {
        $this->table($table, function (Blueprint $table) use ($first_key) {
            $table->index([$first_key]);
        });
    }

    protected function revertIndexes(string $table, string $first_key)
    {
        $this->table($table, function (Blueprint $table) use ($first_key) {
            $table->dropIndex([$first_key]);
        });
    }
}

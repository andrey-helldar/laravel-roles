<?php

use Helldar\Roles\Support\Database\BaseMigration;
use Illuminate\Database\Schema\Blueprint;

;

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

    protected function createPivot(string $table, string $first_table, string $second_table, string $first_key, string $second_key)
    {
        $this->create($table, function (Blueprint $table) use ($first_table, $second_table, $first_key, $second_key) {
            $table->unsignedBigInteger($first_key)->index();
            $table->unsignedBigInteger($second_key);

            $table->foreign($first_key)->references('id')->on($first_table)->onDelete('cascade');
            $table->foreign($second_key)->references('id')->on($second_table)->onDelete('cascade');
        });
    }
}

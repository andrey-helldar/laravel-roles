<?php

use Illuminate\Database\Schema\Blueprint;

class CreateRolesAndPermissionsTables extends BaseMigration
{
    public function up()
    {
        $this->createTable($this->roles);
        $this->createTable($this->permissions);

        $this->createPivot($this->user_roles, $this->users, $this->roles, 'user_id', 'role_id');
        $this->createPivot($this->role_permissions, $this->roles, $this->permissions, 'role_id', 'permission_id');
    }

    public function down()
    {
        $this->schema()->disableForeignKeyConstraints();

        $this->dropTables($this->role_permissions, $this->user_roles, $this->permissions, $this->roles);

        $this->schema()->enableForeignKeyConstraints();
    }

    protected function createTable(string $table)
    {
        $this->schema()
            ->create($table, function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name')->unique();
                $table->timestamps();
            });
    }

    protected function createPivot(string $table, string $first_table, string $second_table, string $first_key, string $second_key)
    {
        $this->schema()->create($table, function (Blueprint $table) use ($first_table, $second_table, $first_key, $second_key) {
            $table->unsignedBigInteger($first_key);
            $table->unsignedBigInteger($second_key);

            $table->foreign($first_key)->references('id')->on($first_table)->onDelete('cascade');
            $table->foreign($second_key)->references('id')->on($second_table)->onDelete('cascade');

            $table->primary([$first_key, $second_key]);
        });
    }

    protected function dropTables(...$tables)
    {
        foreach ($tables as $table) {
            $this->schema()->dropIfExists($table);
        }
    }
}

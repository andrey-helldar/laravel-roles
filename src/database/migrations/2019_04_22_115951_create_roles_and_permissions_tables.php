<?php

use Helldar\Roles\Helpers\Table;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;
use Illuminate\Support\Facades\Schema;

class CreateRolesAndPermissionsTables extends Migration
{
    public function up()
    {
        $this->schema()
            ->create(Table::name('roles'), function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name')->unique();
                $table->timestamps();
            });

        $this->schema()
            ->create(Table::name('permissions'), function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name')->unique();
                $table->timestamps();
            });

        $this->schema()
            ->create(Table::name('user_roles'), function (Blueprint $table) {
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('role_id');

                $table->foreign('user_id')->references('id')->on(Table::name('users'))->onDelete('cascade');
                $table->foreign('role_id')->references('id')->on(Table::name('roles'))->onDelete('cascade');

                $table->primary(['user_id', 'role_id']);
            });

        $this->schema()
            ->create(Table::name('role_permissions'), function (Blueprint $table) {
                $table->unsignedBigInteger('role_id');
                $table->unsignedBigInteger('permission_id');

                $table->foreign('role_id')->references('id')->on(Table::name('roles'))->onDelete('cascade');
                $table->foreign('permission_id')->references('id')->on(Table::name('permissions'))->onDelete('cascade');

                $table->primary(['role_id', 'permission_id']);
            });
    }

    public function down()
    {
        $this->schema()->disableForeignKeyConstraints();

        $tables = Table::all();

        foreach ($tables as $table) {
            $this->schema()->dropIfExists($table);
        }

        $this->schema()->enableForeignKeyConstraints();
    }

    private function schema(): Builder
    {
        return Schema::connection(Table::connection());
    }
}

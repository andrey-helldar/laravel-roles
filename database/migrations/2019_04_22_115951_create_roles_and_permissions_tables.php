<?php

use Helldar\Roles\Facades\Config;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;
use Illuminate\Support\Facades\Schema;

class CreateRolesAndPermissionsTables extends Migration
{
    protected $roles = 'roles';

    protected $permissions = 'permissions';

    protected $user_roles = 'user_roles';

    protected $role_permissions = 'role_permissions';

    protected $users = 'users';

    public function up()
    {
        $this->schema()
            ->create($this->roles, function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name')->unique();
                $table->timestamps();
            });

        $this->schema()
            ->create($this->permissions, function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name')->unique();
                $table->timestamps();
            });

        $this->schema()
            ->create($this->user_roles, function (Blueprint $table) {
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('role_id');

                $table->foreign('user_id')->references('id')->on($this->users)->onDelete('cascade');
                $table->foreign('role_id')->references('id')->on($this->roles)->onDelete('cascade');

                $table->primary(['user_id', 'role_id']);
            });

        $this->schema()
            ->create($this->role_permissions, function (Blueprint $table) {
                $table->unsignedBigInteger('role_id');
                $table->unsignedBigInteger('permission_id');

                $table->foreign('role_id')->references('id')->on($this->roles)->onDelete('cascade');
                $table->foreign('permission_id')->references('id')->on($this->permissions)->onDelete('cascade');

                $table->primary(['role_id', 'permission_id']);
            });
    }

    public function down()
    {
        $this->schema()->disableForeignKeyConstraints();

        $this->schema()->dropIfExists($this->role_permissions);
        $this->schema()->dropIfExists($this->user_roles);
        $this->schema()->dropIfExists($this->permissions);
        $this->schema()->dropIfExists($this->roles);

        $this->schema()->enableForeignKeyConstraints();
    }

    protected function schema(): Builder
    {
        return Schema::connection(
            Config::connection()
        );
    }
}

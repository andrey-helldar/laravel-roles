<?php

namespace Tests\database\seeds;

use Helldar\Roles\Helpers\Table;
use Helldar\Roles\Traits\Models;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\Models\User;

class TableSeeder
{
    use Models;

    public static function run()
    {
        $class = new self;

        $class->truncate();
        $class->create();
    }

    private function truncate()
    {
        Schema::disableForeignKeyConstraints();

        $tables     = Table::all(true);
        $connection = Table::connection();

        foreach ($tables as $table) {
            DB::connection($connection)->table($table)->truncate();
        }

        Schema::enableForeignKeyConstraints();
    }

    /**
     * @throws \Helldar\Roles\Exceptions\UnknownModelKeyException
     */
    private function create()
    {
        $role = $this->role('baz');

        $role->syncPermissions($this->permissions());

        $user = $this->user();

        $user->syncRoles((array) $role->id);
    }

    /**
     * @param string $name
     *
     * @throws \Helldar\Roles\Exceptions\UnknownModelKeyException
     * @return \Helldar\Roles\Models\Role
     */
    private function role(string $name)
    {
        /** @var \Helldar\Roles\Models\Role $model */
        $model = $this->model('role');

        return $model::create(\compact('name'));
    }

    /**
     * @throws \Helldar\Roles\Exceptions\UnknownModelKeyException
     * @return array
     */
    private function permissions(): array
    {
        /** @var \Helldar\Roles\Models\Permission $model */
        $model = $this->model('permission');

        $this->permission('qwerty');
        $this->permission('baz');

        return $model::get()->pluck('id')->toArray();
    }

    /**
     * @param string $name
     *
     * @throws \Helldar\Roles\Exceptions\UnknownModelKeyException
     * @return \Helldar\Roles\Models\Permission
     */
    private function permission(string $name)
    {
        /** @var \Helldar\Roles\Models\Permission $model */
        $model = $this->model('permission');

        return $model::create(\compact('name'));
    }

    private function user()
    {
        return User::create([
            'name'     => 'Admin',
            'email'    => 'test@example.com',
            'password' => Hash::make('qwerty'),
        ]);
    }
}

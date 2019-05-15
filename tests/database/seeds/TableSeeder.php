<?php

namespace Tests\database\seeds;

use Helldar\Roles\Helpers\Table;
use Helldar\Roles\Traits\Find;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\Models\User;

class TableSeeder
{
    use Find;

    /**
     * @throws \Helldar\Roles\Exceptions\UnknownModelKeyException
     */
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
        /** @var \Tests\Models\User $user */
        $user = $this->user();

        // Roles
        $role_1 = $this->role('foo');
        $role_2 = $this->role('bar');
        $role_3 = $this->role('baz');
        $role_4 = $this->role('bax');

        // Permissions
        $permission_1 = $this->permission('foo');
        $permission_2 = $this->permission('bar');
        $permission_3 = $this->permission('baz');
        $permission_4 = $this->permission('bax');

        $role_1->syncPermissions([$permission_1->id, $permission_2->id]);
        $role_2->syncPermissions([$permission_1->id, $permission_2->id]);

        $user->syncRoles([$role_1->id, $role_2->id]);
    }

    private function user()
    {
        return User::create([
            'name'     => 'Admin',
            'email'    => 'test@example.com',
            'password' => Hash::make('qwerty'),
        ]);
    }

    /**
     * @param string $name
     *
     * @throws \Helldar\Roles\Exceptions\UnknownModelKeyException
     *
     * @return \Helldar\Roles\Models\Role
     */
    private function role(string $name)
    {
        /** @var \Helldar\Roles\Models\Role|\Illuminate\Database\Eloquent\Model $model */
        $model = $this->model('role');

        return $model::create(\compact('name'));
    }

    /**
     * @param string $name
     *
     * @throws \Helldar\Roles\Exceptions\UnknownModelKeyException
     *
     * @return \Helldar\Roles\Models\Permission
     */
    private function permission(string $name)
    {
        /** @var \Helldar\Roles\Models\Permission|\Illuminate\Database\Eloquent\Model $model */
        $model = $this->model('permission');

        return $model::create(\compact('name'));
    }
}

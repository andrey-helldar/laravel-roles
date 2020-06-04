<?php

namespace Tests\database\seeds;

use Helldar\Roles\Models\Permission;
use Helldar\Roles\Models\Role;
use Helldar\Roles\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Tests\Models\User;

class TableSeeder
{
    use Searchable;

    public static function run()
    {
        $class = new self();

        $class->create();
    }

    protected function create()
    {
        /** @var User $user */
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

    protected function user()
    {
        return User::create([
            'name'     => 'Admin',
            'email'    => 'test@example.com',
            'password' => Hash::make('qwerty'),
        ]);
    }

    protected function role(string $name): Model
    {
        return Role::create(compact('name'));
    }

    protected function permission(string $name): Model
    {
        return Permission::create(compact('name'));
    }
}

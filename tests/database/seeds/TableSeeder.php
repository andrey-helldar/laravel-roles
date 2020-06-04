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
        $user1 = $this->user();
        $user2 = $this->user('Foo');

        // Roles
        $role_1 = $this->role('foo');
        $role_2 = $this->role('bar', true);
        $role_3 = $this->role('baz');
        $role_4 = $this->role('bax');

        // Permissions
        $permission_1 = $this->permission('foo');
        $permission_2 = $this->permission('bar');
        $permission_3 = $this->permission('baz');
        $permission_4 = $this->permission('bax');

        $role_1->syncPermissions([$permission_1->id, $permission_2->id]);
        $role_2->syncPermissions([$permission_1->id, $permission_2->id]);

        $user1->syncRoles([$role_1->id, $role_2->id]);
        $user2->syncRoles([$role_1->id, $role_3->id]);
    }

    /**
     * @param  string|null  $name
     *
     * @return \Illuminate\Database\Eloquent\Model|\Tests\Models\User
     */
    protected function user(string $name = null)
    {
        return User::create([
            'name'     => $name ?: 'Admin',
            'email'    => $name ? "{$name}@example.com" : 'test@example.com',
            'password' => Hash::make('qwerty'),
        ]);
    }

    protected function role(string $name, bool $is_root = false): Model
    {
        return Role::create(compact('name', 'is_root'));
    }

    protected function permission(string $name): Model
    {
        return Permission::create(compact('name'));
    }
}

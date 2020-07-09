<?php

namespace Tests\PHPUnit;

use Helldar\Roles\Models\Permission;
use Helldar\Roles\Models\Role;
use Tests\TestCase;

final class HasTest extends TestCase
{
    public function testHasUserRoles()
    {
        $user = $this->newUser();

        $this->assertTrue($user->newQuery()->doesntHave('roles')->exists());

        $user->assignRoles(1, 2);

        $this->assertTrue($user->newQuery()->has('roles')->exists());
    }

    public function testHasUserPermissions()
    {
        $user = $this->newUser();

        $this->assertTrue($user->newQuery()->doesntHave('permissions')->exists());

        $user->assignPermissions(1, 2);

        $this->assertTrue($user->newQuery()->has('permissions')->exists());
    }

    public function testHasRolePermissions()
    {
        /** @var \Helldar\Roles\Models\Role $role */
        $role = Role::first();

        $this->assertTrue($role->newQuery()->doesntHave('permissions')->exists());

        $role->syncPermissions([1, 2]);

        $this->assertTrue($role->newQuery()->has('permissions')->exists());
    }

    public function testHasPermissionRoles()
    {
        /** @var \Helldar\Roles\Models\Role $role */
        $role = Role::first();

        /** @var \Helldar\Roles\Models\Permission $permission */
        $permission = Permission::first();

        $this->assertTrue($permission->newQuery()->doesntHave('roles')->exists());

        $role->syncPermissions([$permission->id]);

        $this->assertTrue($permission->newQuery()->has('roles')->exists());
    }
}

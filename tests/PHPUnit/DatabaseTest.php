<?php

namespace Tests\PHPUnit;

use Helldar\Roles\Models\Permission;
use Helldar\Roles\Models\Role;
use Tests\TestCase;

class DatabaseTest extends TestCase
{
    public function testRolesSlugs()
    {
        $role_1 = Role::find(1);
        $role_2 = Role::find(2);
        $role_3 = Role::find(3);
        $role_4 = Role::find(4);
        $role_5 = Role::find(5);

        $this->assertEquals('foo', $role_1->slug);
        $this->assertEquals('bar', $role_2->slug);
        $this->assertEquals('baz', $role_3->slug);
        $this->assertEquals('bax', $role_4->slug);
        $this->assertEquals('qwe_rty', $role_5->slug);
    }

    public function testRolesTitles()
    {
        $role_1 = Role::find(1);
        $role_2 = Role::find(2);
        $role_3 = Role::find(3);
        $role_4 = Role::find(4);
        $role_5 = Role::find(5);

        $this->assertEquals('Foo', $role_1->title);
        $this->assertEquals('BaR', $role_2->title);
        $this->assertEquals('Baz', $role_3->title);
        $this->assertEquals('Bax', $role_4->title);
        $this->assertEquals('Qwe_Rty', $role_5->title);
    }

    public function testPermissionsSlugs()
    {
        $permission_1 = Permission::find(1);
        $permission_2 = Permission::find(2);
        $permission_3 = Permission::find(3);
        $permission_4 = Permission::find(4);
        $permission_5 = Permission::find(5);

        $this->assertEquals('foo', $permission_1->slug);
        $this->assertEquals('bar', $permission_2->slug);
        $this->assertEquals('baz', $permission_3->slug);
        $this->assertEquals('bax', $permission_4->slug);
        $this->assertEquals('qwe_rty', $permission_5->slug);
    }

    public function testPermissionsTitles()
    {
        $permission_1 = Permission::find(1);
        $permission_2 = Permission::find(2);
        $permission_3 = Permission::find(3);
        $permission_4 = Permission::find(4);
        $permission_5 = Permission::find(5);

        $this->assertEquals('Foo', $permission_1->title);
        $this->assertEquals('Bar', $permission_2->title);
        $this->assertEquals('Baz', $permission_3->title);
        $this->assertEquals('Bax', $permission_4->title);
        $this->assertEquals('Qwe_Rty', $permission_5->title);
    }
}

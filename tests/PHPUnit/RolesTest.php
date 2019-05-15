<?php

namespace Tests\PHPUnit;

use Illuminate\Support\Facades\Auth;
use Tests\Models\User;
use Tests\TestCase;

class RolesTest extends TestCase
{
    public function testAccess()
    {
        $user = User::first();

        Auth::login($user, true);

        $role  = $this->call('GET', 'role/access');
        $roles = $this->call('GET', 'roles/access');

        $permission  = $this->call('GET', 'permission/access');
        $permissions = $this->call('GET', 'permissions/access');

        $role->assertStatus(200);
        $roles->assertStatus(200);
        $permission->assertStatus(200);
        $permissions->assertStatus(200);

        $this->assertEquals('ok', $role->getContent());
        $this->assertEquals('ok', $roles->getContent());
        $this->assertEquals('ok', $permission->getContent());
        $this->assertEquals('ok', $permissions->getContent());
    }

    public function testDenied()
    {
        Auth::logout();

        $role  = $this->call('GET', 'role/denied');
        $roles = $this->call('GET', 'roles/denied');

        $permission  = $this->call('GET', 'permission/denied');
        $permissions = $this->call('GET', 'permissions/denied');

        $role->assertStatus(500);
        $roles->assertStatus(500);
        $permission->assertStatus(500);
        $permissions->assertStatus(500);

        $this->assertNotEquals('ok', $role->getContent());
        $this->assertNotEquals('ok', $roles->getContent());
        $this->assertNotEquals('ok', $permission->getContent());
        $this->assertNotEquals('ok', $permissions->getContent());
    }

    public function testPageNotFound()
    {
        $page = $this->call('GET', 'foo');

        $page->assertStatus(404);
    }
}

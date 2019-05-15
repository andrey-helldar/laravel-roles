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

    public function testDeniedUserIsNotAuthorized()
    {
        Auth::logout();

        $role  = $this->call('GET', 'role/denied');
        $roles = $this->call('GET', 'roles/denied');

        $permission  = $this->call('GET', 'permission/denied');
        $permissions = $this->call('GET', 'permissions/denied');

        $role->assertStatus(403);
        $roles->assertStatus(403);
        $permission->assertStatus(403);
        $permissions->assertStatus(403);

        $this->assertNotEquals('ok', $role->getContent());
        $this->assertNotEquals('ok', $roles->getContent());
        $this->assertNotEquals('ok', $permission->getContent());
        $this->assertNotEquals('ok', $permissions->getContent());

        $role->assertSeeText('User is not authorized');
        $roles->assertSeeText('User is not authorized');
        $permission->assertSeeText('User is not authorized');
        $permissions->assertSeeText('User is not authorized');
    }

    public function testDeniedUserNoHavePermissions()
    {
        $user = User::first();

        Auth::login($user, true);

        $roles       = $this->call('GET', 'roles/denied');
        $permissions = $this->call('GET', 'permissions/denied');

        $roles->assertStatus(403);
        $permissions->assertStatus(403);

        $roles->assertSeeText('User does not have permission to view this content. Access is denied.');
        $permissions->assertSeeText('User does not have permission to view this content. Access is denied.');
    }

    public function testPageNotFound()
    {
        $page = $this->call('GET', 'foo');

        $page->assertStatus(404);
    }
}

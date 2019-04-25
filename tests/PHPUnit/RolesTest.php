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

        $roles       = $this->call('GET', 'roles/access');
        $permissions = $this->call('GET', 'permissions/access');

        $roles->assertStatus(200);
        $permissions->assertStatus(200);

        $this->assertEquals('ok', $roles->getContent());
        $this->assertEquals('ok', $permissions->getContent());
    }

    public function testDenied()
    {
        Auth::logout();

        $roles       = $this->call('GET', 'roles/denied');
        $permissions = $this->call('GET', 'permissions/denied');

        $roles->assertStatus(500);
        $permissions->assertStatus(500);

        $this->assertNotEquals('ok', $roles->getContent());
        $this->assertNotEquals('ok', $permissions->getContent());
    }

    public function testPageNotFound()
    {
        $page = $this->call('GET', 'foo');

        $page->assertStatus(404);
    }
}

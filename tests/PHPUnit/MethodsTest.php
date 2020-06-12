<?php

namespace Tests\PHPUnit;

use Helldar\Roles\Facades\Config;
use Tests\TestCase;

class MethodsTest extends TestCase
{
    /**
     * @throws \Throwable
     */
    public function testAssignRoles()
    {
        $user = $this->newUser();

        $this->assertFalse($user->hasRole(1, 'bar'));
        $this->assertFalse($user->hasRoles(1, 'bar'));

        $user->assignRole(1);

        $this->assertTrue($user->hasRole(1, 'bar'));
        $this->assertTrue($user->hasRole('foo', 'bar'));

        $this->assertFalse($user->hasRoles(1, 'bar'));
        $this->assertFalse($user->hasRoles('foo', 'bar'));

        $user->assignRoles('bar');

        $this->assertTrue($user->hasRoles(1, 'bar'));
        $this->assertTrue($user->hasRoles('foo', 'bar'));
    }

    /**
     * @throws \Throwable
     */
    public function testAssignDefaultRole()
    {
        $user = $this->newUser();

        $this->assertFalse($user->hasRole(1));
        $user->assignDefaultRole();
        $this->assertFalse($user->hasRole(1));

        Config::set('default_role', 'foo');

        $user->assignDefaultRole();
        $this->assertTrue($user->hasRole(1));
    }

    /**
     * @throws \Throwable
     */
    public function testRevokeRoles()
    {
        $user = $this->newUser();

        $user->assignRoles(1);
        $user->assignRoles(2);

        $this->assertTrue($user->hasRole(1, 'bar'));
        $this->assertTrue($user->hasRole('foo', 'bar'));

        $this->assertTrue($user->hasRoles(1, 'bar'));
        $this->assertTrue($user->hasRoles('foo', 'bar'));

        $user->revokeRoles('bar');

        $this->assertTrue($user->hasRole(1, 'bar'));
        $this->assertTrue($user->hasRole('foo', 'bar'));

        $this->assertFalse($user->hasRoles(1, 'bar'));
        $this->assertFalse($user->hasRoles('foo', 'bar'));
    }

    /**
     * @throws \Throwable
     */
    public function testSyncRoles()
    {
        $user = $this->newUser();

        $user->assignRole(3);

        $this->assertTrue($user->hasRole(3));

        $this->assertFalse($user->hasRole(1, 'bar'));
        $this->assertFalse($user->hasRoles(1, 'bar'));

        $user->syncRoles([1, 2]);

        $this->assertTrue($user->hasRole(1, 'bar'));
        $this->assertTrue($user->hasRole('foo', 'bar'));
        $this->assertFalse($user->hasRole('baz'));

        $this->assertTrue($user->hasRoles(1, 'bar'));
        $this->assertTrue($user->hasRoles('foo', 'bar'));
        $this->assertFalse($user->hasRoles('foo', 'bar', 'baz'));
    }

    /**
     * @throws \Throwable
     */
    public function testAssignPermissions()
    {
        $user = $this->newUser();

        $this->assertFalse($user->hasPermission(1, 'bar'));
        $this->assertFalse($user->hasPermissions(1, 'bar'));

        $user->assignPermission(1);

        $this->assertTrue($user->hasPermission(1, 'bar'));
        $this->assertTrue($user->hasPermission('foo', 'bar'));

        $this->assertFalse($user->hasPermissions(1, 'bar'));
        $this->assertFalse($user->hasPermissions('foo', 'bar'));

        $user->assignPermissions('bar');

        $this->assertTrue($user->hasPermissions(1, 'bar'));
        $this->assertTrue($user->hasPermissions('foo', 'bar'));
    }

    /**
     * @throws \Throwable
     */
    public function testRevokePermissions()
    {
        $user = $this->newUser();

        $user->assignPermissions(1);
        $user->assignPermissions(2);

        $this->assertTrue($user->hasPermission(1, 'bar'));
        $this->assertTrue($user->hasPermission('foo', 'bar'));

        $this->assertTrue($user->hasPermissions(1, 'bar'));
        $this->assertTrue($user->hasPermissions('foo', 'bar'));

        $user->revokePermissions('bar');

        $this->assertTrue($user->hasPermission(1, 'bar'));
        $this->assertTrue($user->hasPermission('foo', 'bar'));

        $this->assertFalse($user->hasPermissions(1, 'bar'));
        $this->assertFalse($user->hasPermissions('foo', 'bar'));
    }

    /**
     * @throws \Throwable
     */
    public function testSyncPermissions()
    {
        $user = $this->newUser();

        $user->assignPermission(3);

        $this->assertTrue($user->hasPermission(3));

        $this->assertFalse($user->hasPermission(1, 'bar'));
        $this->assertFalse($user->hasPermissions(1, 'bar'));

        $user->syncPermissions([1, 2]);

        $this->assertTrue($user->hasPermission(1, 'bar'));
        $this->assertTrue($user->hasPermission('foo', 'bar'));
        $this->assertFalse($user->hasPermission('baz'));

        $this->assertTrue($user->hasPermissions(1, 'bar'));
        $this->assertTrue($user->hasPermissions('foo', 'bar'));
        $this->assertFalse($user->hasPermissions('foo', 'bar', 'baz'));
    }
}

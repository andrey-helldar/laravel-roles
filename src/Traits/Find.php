<?php

namespace Helldar\Roles\Traits;

use Helldar\Roles\Exceptions\PermissionNotFoundException;
use Helldar\Roles\Exceptions\RoleNotFoundException;
use Helldar\Roles\Models\Permission;
use Helldar\Roles\Models\Role;

trait Find
{
    /**
     * @param string|int|\Helldar\Roles\Models\Role $role
     *
     * @throws \Helldar\Roles\Exceptions\RoleNotFoundException
     * @return \Helldar\Roles\Models\Role
     */
    private function findRole($role): Role
    {
        if ($role instanceof Role) {
            return $role;
        }

        $item = Role::query()
            ->whereId($role)
            ->orWhereName($role)
            ->first();

        if (\is_null($item)) {
            throw new RoleNotFoundException($role);
        }

        return $item;
    }

    /**
     * @param string|int|\Helldar\Roles\Models\Permission $permission
     *
     * @throws \Helldar\Roles\Exceptions\PermissionNotFoundException
     * @return \Helldar\Roles\Models\Permission
     */
    private function findPermission($permission): Permission
    {
        if ($permission instanceof Permission) {
            return $permission;
        }

        $item = Permission::query()
            ->whereId($permission)
            ->orWhereName($permission)
            ->first();

        if (\is_null($item)) {
            throw new PermissionNotFoundException($permission);
        }

        return $item;
    }
}

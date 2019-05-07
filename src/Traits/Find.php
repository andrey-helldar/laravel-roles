<?php

namespace Helldar\Roles\Traits;

use Helldar\Roles\Exceptions\PermissionNotFoundException;
use Helldar\Roles\Exceptions\RoleNotFoundException;

trait Find
{
    use Models;

    /**
     * @param string|int|\Helldar\Roles\Models\Role $role
     *
     * @throws \Helldar\Roles\Exceptions\UnknownModelKeyException
     * @throws \Helldar\Roles\Exceptions\RoleNotFoundException
     *
     * @return \Helldar\Roles\Models\Role|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    private function findRole($role)
    {
        /** @var \Helldar\Roles\Models\Role $model */
        $model = $this->model('role');

        if ($role instanceof $model) {
            return $role;
        }

        $item = $model::query()
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
     * @throws \Helldar\Roles\Exceptions\UnknownModelKeyException
     * @throws \Helldar\Roles\Exceptions\PermissionNotFoundException
     *
     * @return \Helldar\Roles\Models\Permission
     */
    private function findPermission($permission)
    {
        /** @var \Helldar\Roles\Models\Permission $model */
        $model = $this->model('permission');

        if ($permission instanceof $model) {
            return $permission;
        }

        $item = $model::query()
            ->whereId($permission)
            ->orWhereName($permission)
            ->first();

        if (\is_null($item)) {
            throw new PermissionNotFoundException($permission);
        }

        return $item;
    }
}

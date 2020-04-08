<?php

namespace Helldar\Roles\Traits;

use Helldar\Roles\Exceptions\PermissionNotFoundException;
use Helldar\Roles\Exceptions\RoleNotFoundException;
use Helldar\Roles\Exceptions\UnknownModelKeyException;
use Helldar\Roles\Helpers\Config;
use Helldar\Roles\Models\Permission;
use Helldar\Roles\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait Find
{
    /**
     * @param int|Role|string $role
     *
     * @throws RoleNotFoundException
     * @throws UnknownModelKeyException
     *
     * @return Builder|Model|object|Role|null
     */
    protected function findRole($role)
    {
        return $this->findObject('role', $role, RoleNotFoundException::class);
    }

    /**
     * @param int|Permission|string $permission
     *
     * @throws PermissionNotFoundException
     * @throws UnknownModelKeyException
     *
     * @return Permission
     */
    protected function findPermission($permission)
    {
        return $this->findObject('permission', $permission, PermissionNotFoundException::class);
    }

    /**
     * @param string $type
     * @param $value
     * @param string $exception
     *
     * @return \Helldar\Roles\Models\Permission|\Helldar\Roles\Models\Role|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    protected function findObject(string $type, $value, string $exception)
    {
        /** @var Permission|Role $model */
        $model = $this->model($type);

        if ($value instanceof $model) {
            return $value;
        }

        $item = $model::query()
            ->where('id', $value)
            ->orWhere('name', $value)
            ->first();

        if (is_null($item)) {
            throw new $exception($value);
        }

        return $item;
    }

    /**
     * @param string $key
     *
     * @throws UnknownModelKeyException
     *
     * @return Model|Permission|Role
     */
    protected function model(string $key)
    {
        $models = Config::get('models', []);

        if (isset($models[$key])) {
            return $models[$key];
        }

        throw new UnknownModelKeyException($key);
    }
}

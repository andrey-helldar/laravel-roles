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

use function array_key_exists;
use function is_null;

trait Find
{
    /**
     * @param string|int|Role $role
     *
     * @return Role|Builder|Model|object|null
     * @throws RoleNotFoundException
     *
     * @throws UnknownModelKeyException
     */
    protected function findRole($role)
    {
        return $this->findObject('role', $role, RoleNotFoundException::class);
    }

    /**
     * @param string|int|Permission $permission
     *
     * @return Permission
     * @throws PermissionNotFoundException
     *
     * @throws UnknownModelKeyException
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
     * @return Role|Permission|Model
     * @throws UnknownModelKeyException
     *
     */
    protected function model(string $key)
    {
        $models = Config::get('models', []);

        if (array_key_exists($key, $models)) {
            return $models[$key];
        }

        throw new UnknownModelKeyException($key);
    }
}

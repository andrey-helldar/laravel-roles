<?php

namespace Helldar\Roles\Traits;

use function array_key_exists;
use Helldar\Roles\Exceptions\PermissionNotFoundException;
use Helldar\Roles\Exceptions\RoleNotFoundException;
use Helldar\Roles\Exceptions\UnknownModelKeyException;

use Helldar\Roles\Helpers\Config;
use Helldar\Roles\Models\Permission;
use Helldar\Roles\Models\Role;
use Illuminate\Database\Eloquent\Builder;

use Illuminate\Database\Eloquent\Model;
use function is_null;

trait Find
{
    /**
     * @param string|int|Role $role
     *
     * @throws UnknownModelKeyException
     * @throws RoleNotFoundException
     *
     * @return Role|Builder|Model|object|null
     */
    protected function findRole($role)
    {
        /** @var Role $model */
        $model = $this->model('role');

        if ($role instanceof $model) {
            return $role;
        }

        $item = $model::query()
            ->whereId($role)
            ->orWhereName($role)
            ->first();

        if (is_null($item)) {
            throw new RoleNotFoundException($role);
        }

        return $item;
    }

    /**
     * @param string|int|Permission $permission
     *
     * @throws UnknownModelKeyException
     * @throws PermissionNotFoundException
     *
     * @return Permission
     */
    protected function findPermission($permission)
    {
        /** @var Permission $model */
        $model = $this->model('permission');

        if ($permission instanceof $model) {
            return $permission;
        }

        $item = $model::query()
            ->whereId($permission)
            ->orWhereName($permission)
            ->first();

        if (is_null($item)) {
            throw new PermissionNotFoundException($permission);
        }

        return $item;
    }

    /**
     * @param string $key
     *
     * @throws UnknownModelKeyException
     *
     * @return Role|Permission|Model
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

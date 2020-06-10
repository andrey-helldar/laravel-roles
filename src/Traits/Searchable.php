<?php

namespace Helldar\Roles\Traits;

use Closure;
use Helldar\Roles\Exceptions\Core\PermissionNotFoundException;
use Helldar\Roles\Exceptions\Core\RoleNotFoundException;
use Helldar\Roles\Facades\Database\Search;
use Helldar\Roles\Models\Permission;
use Helldar\Roles\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait Searchable
{
    /**
     * @param  \Helldar\Roles\Models\Role|string  $role
     *
     * @throws \Throwable
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function findRole($role)
    {
        return $this->findObject(Role::class, $role, RoleNotFoundException::class);
    }

    /**
     * @param  \Helldar\Roles\Models\Permission|string  $permission
     *
     * @throws \Throwable
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function findPermission($permission)
    {
        return $this->findObject(Permission::class, $permission, PermissionNotFoundException::class);
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Model|string  $model
     * @param  mixed  $value
     * @param  string  $exception
     *
     * @throws \Throwable
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function findObject(string $model, $value, string $exception)
    {
        if ($value instanceof $model) {
            return $value;
        }

        return $this->searchOr($model, $value, function () use ($exception, $value) {
            throw new $exception($value);
        });
    }

    /**
     * @param  \Helldar\Roles\Models\BaseModel|string  $model
     * @param  mixed  $value
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function searchBuilder($model, $value): Builder
    {
        return Search::by($model::query(), $value);
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Model|string  $model
     * @param  mixed  $value
     *
     * @return bool
     */
    protected function searchExist($model, $value): bool
    {
        return $this->searchBuilder($model, $value)->exists();
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Model|string  $model
     * @param  mixed  $value
     * @param  \Closure  $callback
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function searchOr($model, $value, Closure $callback): Model
    {
        return $this->searchBuilder($model, $value)->firstOr(['*'], $callback);
    }
}

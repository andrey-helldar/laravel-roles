<?php

namespace Helldar\Roles\Traits;

use Helldar\Roles\Helpers\Table;
use Helldar\Roles\Models\Permission;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Arr;

/**
 * Trait HasRoles
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Helldar\Roles\Models\Role[] $roles
 */
trait HasRoles
{
    use Find;

    /**
     * @throws \Helldar\Roles\Exceptions\UnknownModelKeyException
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany($this->model('role'), Table::name('user_roles'));
    }

    /**
     * @param string $name
     *
     * @throws \Helldar\Roles\Exceptions\UnknownModelKeyException
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function createRole(string $name)
    {
        return $this->roles()->create(\compact('name'));
    }

    /**
     * @param $role
     *
     * @throws \Helldar\Roles\Exceptions\RoleNotFoundException
     * @throws \Helldar\Roles\Exceptions\UnknownModelKeyException
     */
    public function assignRole($role)
    {
        $role = $this->findRole($role);

        $this->roles()->attach($role->id);
    }

    /**
     * @param string|\Helldar\Roles\Models\Role ...$roles
     */
    public function assignRoles(...$roles)
    {
        \array_map(function ($role) {
            $this->assignRole($role);
        }, $roles);
    }

    /**
     * @param $role
     *
     * @throws \Helldar\Roles\Exceptions\RoleNotFoundException
     * @throws \Helldar\Roles\Exceptions\UnknownModelKeyException
     */
    public function revokeRole($role)
    {
        $role = $this->findRole($role);

        $this->roles()->detach($role->id);
    }

    /**
     * @param string|\Helldar\Roles\Models\Role ...$roles
     */
    public function revokeRoles(...$roles)
    {
        \array_map(function ($role) {
            $this->revokeRole($role);
        }, $roles);
    }

    /**
     * @param array $roles_ids
     *
     * @throws \Helldar\Roles\Exceptions\UnknownModelKeyException
     */
    public function syncRoles(array $roles_ids)
    {
        $this->roles()->sync($roles_ids);
    }

    /**
     * @param string|array ...$roles
     *
     * @return bool
     */
    public function hasRole(...$roles): bool
    {
        foreach (Arr::flatten($roles) as $role) {
            if ($this->roles->contains('name', $role)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string|array ...$roles
     *
     * @return bool
     */
    public function hasRoles(...$roles): bool
    {
        foreach (Arr::flatten($roles) as $role) {
            if (! $this->roles->contains('name', $role)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param string|int|\Helldar\Roles\Models\Permission $permission
     *
     * @throws \Helldar\Roles\Exceptions\UnknownModelKeyException
     *
     * @return bool
     */
    public function hasPermission($permission): bool
    {
        $permission = $this->permissionId($permission);

        return (bool) $this->roles()
            ->whereHas('permissions', function (Builder $builder) use ($permission) {
                $builder
                    ->where('id', $permission)
                    ->orWhere('name', $permission);
            })
            ->exists();
    }

    /**
     * @param string|int|Permission ...$permissions
     *
     * @return bool
     */
    public function hasPermissions(...$permissions): bool
    {
        foreach (Arr::flatten($permissions) as $permission) {
            if (! $this->hasPermission($permission)) {
                return false;
            }
        }

        return true;
    }

    protected function permissionId($permission)
    {
        $model = $this->model('permission');

        return $permission instanceof $model
            ? $permission->id
            : $permission;
    }
}

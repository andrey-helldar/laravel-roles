<?php

namespace Helldar\Roles\Traits;

use Helldar\Roles\Helpers\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Trait HasRoles
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Helldar\Roles\Models\Role[] $roles
 */
trait HasRoles
{
    use Find, Models;

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
     * @param mixed ...$roles
     *
     * @return bool
     */
    public function hasRole(...$roles): bool
    {
        foreach ($roles as $role) {
            if ($this->roles->contains('name', $role)) {
                return true;
            }
        }

        return false;
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
        $model = $this->model('permission');

        if ($permission instanceof $model) {
            $permission = $permission->id;
        }

        return (bool) $this->roles()
            ->whereHas('permissions', function (Builder $builder) use ($permission) {
                $builder
                    ->where('id', $permission)
                    ->orWhere('name', $permission);
            })
            ->exists();
    }
}

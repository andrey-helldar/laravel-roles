<?php

namespace Helldar\Roles\Traits;

use Helldar\Roles\Helpers\Table;
use Helldar\Roles\Models\Permission;
use Helldar\Roles\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Trait HasRoles
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Helldar\Roles\Models\Role[] $roles
 */
trait HasRoles
{
    use Find;

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, Table::name('user_roles'));
    }

    /**
     * @param string $name
     *
     * @return \Illuminate\Database\Eloquent\Model|\Helldar\Roles\Models\Role
     */
    public function createRole(string $name)
    {
        return $this->roles()->create(\compact('name'));
    }

    /**
     * @param string|\Helldar\Roles\Models\Role $role
     *
     * @throws \Helldar\Roles\Exceptions\RoleNotFoundException
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
     * @param string|\Helldar\Roles\Models\Role $role
     *
     * @throws \Helldar\Roles\Exceptions\RoleNotFoundException
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

    public function syncRoles(array $roles_ids)
    {
        $this->roles()->sync($roles_ids);
    }

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
     * @return bool
     */
    public function hasPermission($permission): bool
    {
        if ($permission instanceof Permission) {
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

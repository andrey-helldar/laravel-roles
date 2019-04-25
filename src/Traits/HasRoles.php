<?php

namespace Helldar\Roles\Traits;

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
        return $this->belongsToMany(Role::class, 'user_roles');
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
     * @param string|\Helldar\Roles\Models\Role $role
     *
     * @throws \Helldar\Roles\Exceptions\RoleNotFoundException
     */
    public function revokeRole($role)
    {
        $role = $this->findRole($role);

        $this->roles()->detach($role->id);
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

    public function hasPermissionThroughRole($permission): bool
    {
        foreach ($permission->roles as $role) {
            if ($this->roles->contains($role)) {
                return true;
            }
        }

        return false;
    }

    public function hasPermissionTo($permission): bool
    {
        return $this->hasPermission($permission) || $this->hasPermissionThroughRole($permission);
    }

    public function hasPermission($permission): bool
    {
        return (bool) $this->roles()
            ->whereHas('permissions', function (Builder $builder) use ($permission) {
                $builder->where('name', $permission);
            })
            ->exists();
    }
}

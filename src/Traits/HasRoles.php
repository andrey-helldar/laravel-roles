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
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles');
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

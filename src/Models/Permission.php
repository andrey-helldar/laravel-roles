<?php

namespace Helldar\Roles\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection|\Helldar\Roles\Models\Role[] $roles
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class Permission extends BaseModel
{
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * @param  \Helldar\Roles\Models\Role|string  $role
     *
     * @throws \Throwable
     */
    public function assignRole($role): void
    {
        $role = $this->findRole($role);

        $this->roles()->attach($role->id);
    }

    /**
     * @param  \Helldar\Roles\Models\Role[]|string[]  $roles
     *
     * @throws \Throwable
     */
    public function assignRoles(...$roles): void
    {
        foreach ($roles as $role) {
            $this->assignRole($role);
        }
    }

    /**
     * @param  \Helldar\Roles\Models\Role|string  $role
     *
     * @throws \Throwable
     */
    public function revokeRole($role): void
    {
        $role = $this->findRole($role);

        $this->roles()->detach($role->id);
    }

    /**
     * @param  \Helldar\Roles\Models\Role[]|string[]  $roles
     *
     * @throws \Throwable
     */
    public function revokeRoles(...$roles): void
    {
        foreach ($roles as $role) {
            $this->revokeRole($role);
        }
    }

    /**
     * @param  int[]  $roles_ids
     *
     * @return array
     */
    public function syncRoles(array $roles_ids): array
    {
        return $this->roles()->sync($roles_ids);
    }
}

<?php

namespace Helldar\Roles\Models;

use Helldar\Roles\Constants\Tables;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property \Helldar\Roles\Models\Role[]|\Illuminate\Database\Eloquent\Collection $roles
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class Permission extends BaseModel
{
    protected $table = Tables::PERMISSIONS;

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, Tables::ROLE_PERMISSION);
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

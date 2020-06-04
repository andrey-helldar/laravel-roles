<?php

namespace Helldar\Roles\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property \Helldar\Roles\Models\Permission[]|\Illuminate\Database\Eloquent\Collection $permissions
 */
class Role extends BaseModel
{
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permission');
    }

    public function createPermission(string $name): Model
    {
        return $this->permissions()->create(compact('name'));
    }

    /**
     * @param  \Helldar\Roles\Models\Permission|string  $permission
     *
     * @throws \Throwable
     */
    public function assignPermission($permission): void
    {
        $permission = $this->findPermission($permission);

        $this->permissions()->attach($permission->id);
    }

    /**
     * @param  \Helldar\Roles\Models\Permission[]|string[]  $permissions
     *
     * @throws \Throwable
     */
    public function assignPermissions(...$permissions): void
    {
        foreach ($permissions as $permission) {
            $this->assignPermission($permission);
        }
    }

    /**
     * @param  \Helldar\Roles\Models\Permission|string  $permission
     *
     * @throws \Throwable
     */
    public function revokePermission($permission): void
    {
        $permission = $this->findPermission($permission);

        $this->permissions()->detach($permission->id);
    }

    /**
     * @param  \Helldar\Roles\Models\Permission[]|string[]  $permissions
     *
     * @throws \Throwable
     */
    public function revokePermissions(...$permissions): void
    {
        foreach ($permissions as $permission) {
            $this->revokePermission($permission);
        }
    }

    /**
     * @param  int[]  $permissions_ids
     */
    public function syncPermissions(array $permissions_ids): void
    {
        $this->permissions()->sync($permissions_ids);
    }

    /**
     * @param  \Helldar\Roles\Models\Permission|string  $permission
     *
     * @return bool
     */
    public function hasPermission($permission): bool
    {
        if ($permission instanceof Permission) {
            $permission = $permission->id;
        }

        return $this->permissions()
            ->where('id', $permission)
            ->orWhere('name', $permission)
            ->exists();
    }
}

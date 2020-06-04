<?php

namespace Helldar\Roles\Traits;

use Helldar\Roles\Models\Permission;
use Helldar\Roles\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Arr;

/**
 * @property \Helldar\Roles\Models\Role[]|\Illuminate\Database\Eloquent\Collection $roles
 * @property \Helldar\Roles\Models\Permission[]|\Illuminate\Database\Eloquent\Collection $permissions
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
trait HasRoles
{
    use Searchable;
    use Cacheable;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany|\Helldar\Roles\Models\BaseModel
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_role');
    }

    public function hasRootRole(): bool
    {
        return $this->cache(__FUNCTION__, function () {
            return $this->roles()
                ->where('is_root', true)
                ->exists();
        });
    }

    public function createRole(string $name): Model
    {
        return $this->roles()->create(compact('name'));
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
     */
    public function syncRoles(array $roles_ids): void
    {
        $this->roles()->sync($roles_ids);
    }

    /**
     * @param  \Helldar\Roles\Models\Role[]|string[]  $roles
     *
     * @return bool
     */
    public function hasRole(...$roles): bool
    {
        return $this->cache(__FUNCTION__, function () use ($roles) {
            foreach (Arr::flatten($roles) as $role) {
                if ($this->roles->contains('name', $role)) {
                    return true;
                }
            }

            return false;
        }, $roles);
    }

    /**
     * @param  \Helldar\Roles\Models\Role[]|string[]  $roles
     *
     * @return bool
     */
    public function hasRoles(...$roles): bool
    {
        return $this->cache(__FUNCTION__, function () use ($roles) {
            foreach (Arr::flatten($roles) as $role) {
                if (! $this->roles->contains('name', $role)) {
                    return false;
                }
            }

            return true;
        }, $roles);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany|\Helldar\Roles\Models\BaseModel
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'user_permission');
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
        return $this->cache(__FUNCTION__, function () use ($permission) {
            $permission = $this->permissionId($permission);

            return $this->permissions()
                    ->searchBy($permission)
                    ->exists()
                ||
                $this->roles()
                    ->whereHas('permissions', function (Builder $builder) use ($permission) {
                        $builder->searchBy($permission);
                    })->exists();
        }, $permission);
    }

    /**
     * @param  \Helldar\Roles\Models\Permission[]|string[]  $permissions
     *
     * @return bool
     */
    public function hasPermissions(...$permissions): bool
    {
        return $this->cache(__FUNCTION__, function () use ($permissions) {
            foreach (Arr::flatten($permissions) as $permission) {
                if (! $this->hasPermission($permission)) {
                    return false;
                }
            }

            return true;
        }, $permissions);
    }

    /**
     * @param  \Helldar\Roles\Models\Permission|string  $permission
     *
     * @return int|string
     */
    protected function permissionId($permission)
    {
        return $permission instanceof Permission
            ? $permission->id
            : $permission;
    }
}

<?php

namespace Helldar\Roles\Traits;

use Helldar\Roles\Facades\Config;
use Helldar\Roles\Models\Permission;
use Helldar\Roles\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Arr;

/**
 * @property \Helldar\Roles\Models\Role[]|\Illuminate\Database\Eloquent\Collection $roles
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
trait HasRoles
{
    use Searchable;
    use Cacheable;

    public function hasRootRole(): bool
    {
        return $this->cache(__FUNCTION__, function () {
            if ($roles = Config::rootRoles()) {
                return $this->roles()
                    ->whereIn('name', $roles)
                    ->exists();
            }

            return false;
        });
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_role');
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
     * @param  \Helldar\Roles\Models\Permission|string  $permission
     *
     * @return bool
     */
    public function hasPermission($permission): bool
    {
        return $this->cache(__FUNCTION__, function () use ($permission) {
            $permission = $this->permissionId($permission);

            return (bool) $this->roles()
                ->whereHas('permissions', function (Builder $builder) use ($permission) {
                    $builder
                        ->where('id', $permission)
                        ->orWhere('name', $permission);
                })
                ->exists();
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

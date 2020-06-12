<?php

namespace Helldar\Roles\Traits;

use Helldar\Roles\Facades\Config;
use Helldar\Roles\Facades\Database\Search;
use Helldar\Roles\Models\Permission;
use Helldar\Roles\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
     * @return \Helldar\Roles\Models\BaseModel|\Illuminate\Database\Eloquent\Relations\BelongsToMany
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

    public function createRole(string $slug, string $title = null, bool $is_root = false): Model
    {
        return $this->roles()->create(compact('slug', 'title', 'is_root'));
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
     * @throws \Throwable
     */
    public function assignDefaultRole(): void
    {
        if ($role = Config::defaultRole()) {
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
            return Search::by($this->roles(), $roles)->exists();
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
            $count = Search::by($this->roles(), $roles)->count();

            return $count === count($roles);
        }, $roles);
    }

    /**
     * @return \Helldar\Roles\Models\BaseModel|\Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'user_permission');
    }

    public function createPermission(string $slug, string $title = null): Model
    {
        return $this->permissions()->create(compact('slug', 'title'));
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
     * @param  \Helldar\Roles\Models\Permission[]|int[]|string[]  $permission
     *
     * @return bool
     */
    public function hasPermission(...$permission): bool
    {
        return $this->cache(__FUNCTION__, function () use ($permission) {
            $first = Search::by($this->permissions(), $permission)->exists();

            $second = $this->roles()
                ->whereHas('permissions', function (Builder $builder) use ($permission) {
                    return Search::by($builder, $permission);
                })->exists();

            return $first || $second;
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
            $count = Search::by($this->permissions(), $permissions)->count();

            return $count === count($permissions);
        }, $permissions);
    }
}

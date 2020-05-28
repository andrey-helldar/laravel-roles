<?php

namespace Helldar\Roles\Traits;

use Helldar\Roles\Exceptions\RoleNotFoundException;
use Helldar\Roles\Exceptions\UnknownModelKeyException;
use Helldar\Roles\Helpers\Table;
use Helldar\Roles\Models\Permission;
use Helldar\Roles\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Arr;

use function compact;

/**
 * Trait HasRoles.
 *
 * @property Collection|Role[] $roles
 */
trait HasRoles
{
    use Find;
    use Cacheable;

    /**
     * @throws UnknownModelKeyException
     *
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany($this->model('role'), Table::name('user_roles'));
    }

    /**
     * @param string $name
     *
     * @throws UnknownModelKeyException
     *
     * @return Model
     */
    public function createRole(string $name)
    {
        return $this->roles()->create(compact('name'));
    }

    /**
     * @param $role
     *
     * @throws RoleNotFoundException
     * @throws UnknownModelKeyException
     */
    public function assignRole($role)
    {
        $role = $this->findRole($role);

        $this->roles()->attach($role->id);
    }

    /**
     * @param Role|string ...$roles
     */
    public function assignRoles(...$roles)
    {
        foreach ($roles as $role) {
            $this->assignRole($role);
        }
    }

    /**
     * @param $role
     *
     * @throws RoleNotFoundException
     * @throws UnknownModelKeyException
     */
    public function revokeRole($role)
    {
        $role = $this->findRole($role);

        $this->roles()->detach($role->id);
    }

    /**
     * @param Role|string ...$roles
     */
    public function revokeRoles(...$roles)
    {
        foreach ($roles as $role) {
            $this->revokeRole($role);
        }
    }

    /**
     * @param array $roles_ids
     *
     * @throws UnknownModelKeyException
     */
    public function syncRoles(array $roles_ids)
    {
        $this->roles()->sync($roles_ids);
    }

    /**
     * @param array|string ...$roles
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
     * @param array|string ...$roles
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
     * @param int|Permission|string $permission
     *
     * @throws UnknownModelKeyException
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
     * @param int|Permission|string ...$permissions
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

    protected function permissionId($permission)
    {
        $model = $this->model('permission');

        return $permission instanceof $model
            ? $permission->id
            : $permission;
    }
}

<?php

namespace Helldar\Roles\Traits;

use function array_map;
use function compact;
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

/**
 * Trait HasRoles
 *
 * @property-read Collection|Role[] $roles
 */
trait HasRoles
{
    use Find;

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
     * @param string|Role ...$roles
     */
    public function assignRoles(...$roles)
    {
        array_map(function ($role) {
            $this->assignRole($role);
        }, $roles);
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
     * @param string|Role ...$roles
     */
    public function revokeRoles(...$roles)
    {
        array_map(function ($role) {
            $this->revokeRole($role);
        }, $roles);
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
     * @param string|array ...$roles
     *
     * @return bool
     */
    public function hasRole(...$roles): bool
    {
        foreach (Arr::flatten($roles) as $role) {
            if ($this->roles->contains('name', $role)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string|array ...$roles
     *
     * @return bool
     */
    public function hasRoles(...$roles): bool
    {
        foreach (Arr::flatten($roles) as $role) {
            if (!$this->roles->contains('name', $role)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param string|int|Permission $permission
     *
     * @throws UnknownModelKeyException
     *
     * @return bool
     */
    public function hasPermission($permission): bool
    {
        $permission = $this->permissionId($permission);

        return (bool) $this->roles()
            ->whereHas('permissions', function (Builder $builder) use ($permission) {
                $builder
                    ->where('id', $permission)
                    ->orWhere('name', $permission);
            })
            ->exists();
    }

    /**
     * @param string|int|Permission ...$permissions
     *
     * @return bool
     */
    public function hasPermissions(...$permissions): bool
    {
        foreach (Arr::flatten($permissions) as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }

        return true;
    }

    protected function permissionId($permission)
    {
        $model = $this->model('permission');

        return $permission instanceof $model
            ? $permission->id
            : $permission;
    }
}

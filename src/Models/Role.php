<?php

namespace Helldar\Roles\Models;

use Helldar\Roles\Contracts\Role as RoleContract;
use Helldar\Roles\Helpers\Table;
use Helldar\Roles\Traits\Find;
use Helldar\Roles\Traits\Models;
use Helldar\Roles\Traits\SetAttribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Helldar\Roles\Models\Role
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Helldar\Roles\Models\Permission[] $permissions
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Helldar\Roles\Models\Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Helldar\Roles\Models\Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Helldar\Roles\Models\Role orWhereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Helldar\Roles\Models\Role orWhereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Helldar\Roles\Models\Role query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Helldar\Roles\Models\Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Helldar\Roles\Models\Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Helldar\Roles\Models\Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Helldar\Roles\Models\Role whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Role extends Model implements RoleContract
{
    use SetAttribute, Find, Models;

    protected $fillable = ['name'];

    public function __construct(array $attributes = [])
    {
        $this->connection = Table::connection();
        $this->table      = Table::name('roles');

        parent::__construct($attributes);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     *
     * @throws \Helldar\Roles\Exceptions\UnknownModelKeyException
     *
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany($this->model('permission'), Table::name('role_permissions'));
    }

    /**
     * @param string $name
     *
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \Helldar\Roles\Exceptions\UnknownModelKeyException
     *
     */
    public function createPermission(string $name)
    {
        return $this->permissions()->create(\compact('name'));
    }

    /**
     * @param string|\Helldar\Roles\Models\Permission $permission
     *
     * @throws \Helldar\Roles\Exceptions\PermissionNotFoundException
     * @throws \Helldar\Roles\Exceptions\UnknownModelKeyException
     */
    public function assignPermission($permission)
    {
        $permission = $this->findPermission($permission);

        $this->permissions()->attach($permission->id);
    }

    /**
     * @param string|\Helldar\Roles\Models\Permission ...$permissions
     */
    public function assignPermissions(...$permissions)
    {
        \array_map(function ($permission) {
            $this->assignPermission($permission);
        }, $permissions);
    }

    /**
     * @param string|\Helldar\Roles\Models\Permission $permission
     *
     * @throws \Helldar\Roles\Exceptions\PermissionNotFoundException
     * @throws \Helldar\Roles\Exceptions\UnknownModelKeyException
     */
    public function revokePermission($permission)
    {
        $permission = $this->findPermission($permission);

        $this->permissions()->detach([$permission->id]);
    }

    /**
     * @param string|\Helldar\Roles\Models\Permission ...$permissions
     */
    public function revokePermissions(...$permissions)
    {
        \array_map(function ($permission) {
            $this->revokePermission($permission);
        }, $permissions);
    }

    /**
     * @param array $permissions_ids
     *
     * @throws \Helldar\Roles\Exceptions\UnknownModelKeyException
     */
    public function syncPermissions(array $permissions_ids)
    {
        $this->permissions()->sync($permissions_ids);
    }

    /**
     * @param string|int|\Helldar\Roles\Models\Permission $permission
     *
     * @return bool
     * @throws \Helldar\Roles\Exceptions\UnknownModelKeyException
     *
     */
    public function hasPermission($permission): bool
    {
        $model = $this->model('permission');

        if ($permission instanceof $model) {
            $permission = $permission->id;
        }

        return (bool) $this->permissions()
            ->whereId($permission)
            ->orWhereName($permission)
            ->exists();
    }
}

<?php

namespace Helldar\Roles\Models;

use Helldar\Roles\Contracts\Permission as PermissionContract;
use Helldar\Roles\Helpers\Table;
use Helldar\Roles\Traits\Find;
use Helldar\Roles\Traits\Models;
use Helldar\Roles\Traits\SetAttribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Helldar\Roles\Models\Permission
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Helldar\Roles\Models\Role[] $roles
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Helldar\Roles\Models\Permission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Helldar\Roles\Models\Permission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Helldar\Roles\Models\Permission orWhereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Helldar\Roles\Models\Permission orWhereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Helldar\Roles\Models\Permission query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Helldar\Roles\Models\Permission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Helldar\Roles\Models\Permission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Helldar\Roles\Models\Permission whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Helldar\Roles\Models\Permission whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Permission extends Model implements PermissionContract
{
    use SetAttribute, Find, Models;

    protected $fillable = ['name'];

    public function __construct(array $attributes = [])
    {
        $this->connection = Table::connection();
        $this->table      = Table::name('permissions');

        parent::__construct($attributes);
    }

    /**
     * @throws \Helldar\Roles\Exceptions\UnknownModelKeyException
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany($this->model('role'), Table::name('role_permissions'));
    }

    /**
     * @param string|\Helldar\Roles\Models\Role $role
     *
     * @throws \Helldar\Roles\Exceptions\RoleNotFoundException
     * @throws \Helldar\Roles\Exceptions\UnknownModelKeyException
     */
    public function assignRole($role)
    {
        $role = $this->findRole($role);

        $this->roles()->attach($role->id);
    }

    /**
     * @param string|\Helldar\Roles\Models\Role ...$roles
     */
    public function assignRoles(...$roles)
    {
        \array_map(function ($role) {
            $this->assignRole($role);
        }, $roles);
    }

    /**
     * @param string|\Helldar\Roles\Models\Role $role
     *
     * @throws \Helldar\Roles\Exceptions\RoleNotFoundException
     * @throws \Helldar\Roles\Exceptions\UnknownModelKeyException
     */
    public function revokeRole($role)
    {
        $role = $this->findRole($role);

        $this->roles()->detach([$role->id]);
    }

    /**
     * @param string|\Helldar\Roles\Models\Role ...$roles
     */
    public function revokeRoles(...$roles)
    {
        \array_map(function ($role) {
            $this->revokeRole($role);
        }, $roles);
    }

    /**
     * @param array $roles_ids
     *
     * @throws \Helldar\Roles\Exceptions\UnknownModelKeyException
     */
    public function syncRoles(array $roles_ids)
    {
        $this->roles()->sync($roles_ids);
    }
}

<?php

namespace Helldar\Roles\Models;

use Helldar\Roles\Helpers\Table;
use Helldar\Roles\Traits\Find;
use Helldar\Roles\Traits\SetAttribute;
use Illuminate\Database\Eloquent\Model;

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
 * @method static \Illuminate\Database\Eloquent\Builder|\Helldar\Roles\Models\Permission query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Helldar\Roles\Models\Permission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Helldar\Roles\Models\Permission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Helldar\Roles\Models\Permission whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Helldar\Roles\Models\Permission whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Permission extends Model
{
    use SetAttribute, Find;

    protected $fillable = ['name'];

    public function __construct(array $attributes = [])
    {
        $this->connection = Table::connection();
        $this->table      = Table::name('permissions');

        parent::__construct($attributes);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, Table::name('role_permissions'));
    }

    /**
     * @param string|\Helldar\Roles\Models\Role $role
     *
     * @throws \Helldar\Roles\Exceptions\RoleNotFoundException
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

    public function syncRoles(array $roles_ids)
    {
        $this->roles()->sync($roles_ids);
    }
}

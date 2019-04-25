<?php

namespace Helldar\Roles\Models;

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
    use SetAttribute;

    protected $fillable = ['name'];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permissions');
    }

    /**
     * @param string|\Helldar\Roles\Models\Role $role
     */
    public function assignRole($role)
    {
        if (!($role instanceof Role)) {
            $role = Role::whereName($role)->firstOrFail();
        }

        $this->roles()->attach($role->id);
    }

    /**
     * @param string|\Helldar\Roles\Models\Role $role
     */
    public function revokeRole($role)
    {
        if (!($role instanceof Role)) {
            $role = Role::whereName($role)->firstOrFail();
        }

        $this->roles()->detach([$role->id]);
    }

    public function syncRoles(array $roles_ids)
    {
        $this->roles()->sync($roles_ids);
    }
}

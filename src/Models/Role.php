<?php

namespace Helldar\Roles\Models;

use Helldar\Roles\Traits\SetAttribute;
use Illuminate\Database\Eloquent\Model;

/**
 * Helldar\Roles\Models\Role
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Helldar\Roles\Models\Permission[] $permissions
 * @method static \Illuminate\Database\Eloquent\Builder|\Helldar\Roles\Models\Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Helldar\Roles\Models\Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Helldar\Roles\Models\Role query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Helldar\Roles\Models\Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Helldar\Roles\Models\Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Helldar\Roles\Models\Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Helldar\Roles\Models\Role whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Role extends Model
{
    use SetAttribute;

    protected $fillable = ['name'];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions');
    }

    /**
     * @param string|\Helldar\Roles\Models\Permission $permission
     */
    public function assignPermission($permission)
    {
        if (!($permission instanceof Permission)) {
            $permission = Permission::whereName($permission)->firstOrFail();
        }

        $this->permissions()->attach($permission->id);
    }

    /**
     * @param string|\Helldar\Roles\Models\Permission $permission
     */
    public function revokePermission($permission)
    {
        if (!($permission instanceof Permission)) {
            $permission = Permission::whereName($permission)->firstOrFail();
        }

        $this->permissions()->detach([$permission->id]);
    }

    public function syncPermissions(array $permissions_ids)
    {
        $this->permissions()->sync($permissions_ids);
    }
}

<?php

namespace Helldar\Roles\Models;

use Eloquent;
use Helldar\Roles\Contracts\Permission as PermissionContract;
use Helldar\Roles\Exceptions\RoleNotFoundException;
use Helldar\Roles\Exceptions\UnknownModelKeyException;
use Helldar\Roles\Helpers\Table;
use Helldar\Roles\Traits\Find;
use Helldar\Roles\Traits\SetAttribute;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use Illuminate\Support\Carbon;

use function array_map;

/**
 * Helldar\Roles\Models\Permission
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Role[] $roles
 *
 * @method static Builder|Permission newModelQuery()
 * @method static Builder|Permission newQuery()
 * @method static Builder|Permission orWhereId($value)
 * @method static Builder|Permission orWhereName($value)
 * @method static Builder|Permission query()
 * @method static Builder|Permission whereCreatedAt($value)
 * @method static Builder|Permission whereId($value)
 * @method static Builder|Permission whereName($value)
 * @method static Builder|Permission whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Permission extends Model implements PermissionContract
{
    use SetAttribute, Find;

    protected $fillable = ['name'];

    public function __construct(array $attributes = [])
    {
        $this->connection = Table::connection();
        $this->table      = Table::name('permissions');

        parent::__construct($attributes);
    }

    /**
     * @throws UnknownModelKeyException
     *
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany($this->model('role'), Table::name('role_permissions'));
    }

    /**
     * @param string|Role $role
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
     * @param string|Role $role
     *
     * @throws RoleNotFoundException
     * @throws UnknownModelKeyException
     */
    public function revokeRole($role)
    {
        $role = $this->findRole($role);

        $this->roles()->detach([$role->id]);
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
}

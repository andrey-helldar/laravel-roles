<?php

namespace Helldar\Roles\Models;

use function array_map;
use function compact;
use Eloquent;
use Helldar\Roles\Contracts\Role as RoleContract;
use Helldar\Roles\Exceptions\PermissionNotFoundException;
use Helldar\Roles\Exceptions\UnknownModelKeyException;
use Helldar\Roles\Helpers\Table;
use Helldar\Roles\Traits\Find;
use Helldar\Roles\Traits\SetAttribute;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * Helldar\Roles\Models\Role
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Permission[] $permissions
 *
 * @method static Builder|Role newModelQuery()
 * @method static Builder|Role newQuery()
 * @method static Builder|Role orWhereId($value)
 * @method static Builder|Role orWhereName($value)
 * @method static Builder|Role query()
 * @method static Builder|Role whereCreatedAt($value)
 * @method static Builder|Role whereId($value)
 * @method static Builder|Role whereName($value)
 * @method static Builder|Role whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Role extends Model implements RoleContract
{
    use SetAttribute, Find;

    protected $fillable = ['name'];

    public function __construct(array $attributes = [])
    {
        $this->connection = Table::connection();
        $this->table      = Table::name('roles');

        parent::__construct($attributes);
    }

    /**
     * @throws UnknownModelKeyException
     *
     * @return BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany($this->model('permission'), Table::name('role_permissions'));
    }

    /**
     * @param string $name
     *
     * @throws UnknownModelKeyException
     *
     * @return Model
     */
    public function createPermission(string $name)
    {
        return $this->permissions()->create(compact('name'));
    }

    /**
     * @param string|Permission $permission
     *
     * @throws PermissionNotFoundException
     * @throws UnknownModelKeyException
     */
    public function assignPermission($permission)
    {
        $permission = $this->findPermission($permission);

        $this->permissions()->attach($permission->id);
    }

    /**
     * @param string|Permission ...$permissions
     */
    public function assignPermissions(...$permissions)
    {
        array_map(function ($permission) {
            $this->assignPermission($permission);
        }, $permissions);
    }

    /**
     * @param string|Permission $permission
     *
     * @throws PermissionNotFoundException
     * @throws UnknownModelKeyException
     */
    public function revokePermission($permission)
    {
        $permission = $this->findPermission($permission);

        $this->permissions()->detach([$permission->id]);
    }

    /**
     * @param string|Permission ...$permissions
     */
    public function revokePermissions(...$permissions)
    {
        array_map(function ($permission) {
            $this->revokePermission($permission);
        }, $permissions);
    }

    /**
     * @param array $permissions_ids
     *
     * @throws UnknownModelKeyException
     */
    public function syncPermissions(array $permissions_ids)
    {
        $this->permissions()->sync($permissions_ids);
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

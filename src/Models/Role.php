<?php

namespace Helldar\Roles\Models;

use Helldar\Roles\Constants\Tables;
use Helldar\Roles\Facades\Database\Search;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property \Helldar\Roles\Models\Permission[]|\Illuminate\Database\Eloquent\Collection $permissions
 * @property bool $is_root
 */
class Role extends BaseModel
{
    protected $table = Tables::ROLES;

    protected $fillable = ['slug', 'title', 'is_root'];

    protected $casts = [
        'is_root' => 'boolean',
    ];

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, Tables::ROLE_PERMISSION);
    }

    public function createPermission(string $slug): Model
    {
        return $this->permissions()->create(compact('slug'));
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
     * @param  \Helldar\Roles\Models\Permission|string  $permission
     *
     * @return bool
     */
    public function hasPermission($permission): bool
    {
        return Search::by($this->permissions(), $permission)->exists();
    }
}

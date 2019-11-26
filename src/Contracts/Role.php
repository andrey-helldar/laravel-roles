<?php

namespace Helldar\Roles\Contracts;

use Helldar\Roles\Exceptions\PermissionNotFoundException;
use Helldar\Roles\Exceptions\UnknownModelKeyException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

interface Role
{
    public function permissions(): BelongsToMany;

    /**
     * @param string $name
     *
     * @throws UnknownModelKeyException
     *
     * @return Model
     */
    public function createPermission(string $name);

    /**
     * @param string|\Helldar\Roles\Models\Permission $permission
     *
     * @throws PermissionNotFoundException
     * @throws UnknownModelKeyException
     */
    public function assignPermission($permission);

    /**
     * @param string|\Helldar\Roles\Models\Permission ...$permissions
     */
    public function assignPermissions(...$permissions);

    /**
     * @param string|\Helldar\Roles\Models\Permission $permission
     *
     * @throws PermissionNotFoundException
     * @throws UnknownModelKeyException
     */
    public function revokePermission($permission);

    /**
     * @param string|\Helldar\Roles\Models\Permission ...$permissions
     */
    public function revokePermissions(...$permissions);

    /**
     * @param array $permissions_ids
     *
     * @throws UnknownModelKeyException
     */
    public function syncPermissions(array $permissions_ids);

    /**
     * @param string|int|\Helldar\Roles\Models\Permission $permission
     *
     * @throws UnknownModelKeyException
     *
     * @return bool
     */
    public function hasPermission($permission): bool;
}

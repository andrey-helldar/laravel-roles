<?php

namespace Helldar\Roles\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

interface Role
{
    public function permissions(): BelongsToMany;

    /**
     * @param string $name
     *
     * @throws \Helldar\Roles\Exceptions\UnknownModelKeyException
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function createPermission(string $name);

    /**
     * @param string|\Helldar\Roles\Models\Permission $permission
     *
     * @throws \Helldar\Roles\Exceptions\PermissionNotFoundException
     * @throws \Helldar\Roles\Exceptions\UnknownModelKeyException
     */
    public function assignPermission($permission);

    /**
     * @param string|\Helldar\Roles\Models\Permission ...$permissions
     */
    public function assignPermissions(...$permissions);

    /**
     * @param string|\Helldar\Roles\Models\Permission $permission
     *
     * @throws \Helldar\Roles\Exceptions\PermissionNotFoundException
     * @throws \Helldar\Roles\Exceptions\UnknownModelKeyException
     */
    public function revokePermission($permission);

    /**
     * @param string|\Helldar\Roles\Models\Permission ...$permissions
     */
    public function revokePermissions(...$permissions);

    /**
     * @param array $permissions_ids
     *
     * @throws \Helldar\Roles\Exceptions\UnknownModelKeyException
     */
    public function syncPermissions(array $permissions_ids);

    /**
     * @param string|int|\Helldar\Roles\Models\Permission $permission
     *
     * @throws \Helldar\Roles\Exceptions\UnknownModelKeyException
     *
     * @return bool
     */
    public function hasPermission($permission): bool;
}
<?php

namespace Helldar\Roles\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

interface Permission
{
    /**
     * @throws \Helldar\Roles\Exceptions\UnknownModelKeyException
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles(): BelongsToMany;

    /**
     * @param string|\Helldar\Roles\Models\Role $role
     *
     * @throws \Helldar\Roles\Exceptions\RoleNotFoundException
     * @throws \Helldar\Roles\Exceptions\UnknownModelKeyException
     */
    public function assignRole($role);

    /**
     * @param string|\Helldar\Roles\Models\Role ...$roles
     */
    public function assignRoles(...$roles);

    /**
     * @param string|\Helldar\Roles\Models\Role $role
     *
     * @throws \Helldar\Roles\Exceptions\RoleNotFoundException
     * @throws \Helldar\Roles\Exceptions\UnknownModelKeyException
     */
    public function revokeRole($role);

    /**
     * @param string|\Helldar\Roles\Models\Role ...$roles
     */
    public function revokeRoles(...$roles);

    /**
     * @param array $roles_ids
     *
     * @throws \Helldar\Roles\Exceptions\UnknownModelKeyException
     */
    public function syncRoles(array $roles_ids);
}

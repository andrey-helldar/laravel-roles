<?php

namespace Helldar\Roles\Contracts;

use Helldar\Roles\Exceptions\RoleNotFoundException;
use Helldar\Roles\Exceptions\UnknownModelKeyException;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

interface Permission
{
    /**
     * @throws UnknownModelKeyException
     *
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany;

    /**
     * @param \Helldar\Roles\Models\Role|string $role
     *
     * @throws RoleNotFoundException
     * @throws UnknownModelKeyException
     */
    public function assignRole($role);

    /**
     * @param \Helldar\Roles\Models\Role|string ...$roles
     */
    public function assignRoles(...$roles);

    /**
     * @param \Helldar\Roles\Models\Role|string $role
     *
     * @throws RoleNotFoundException
     * @throws UnknownModelKeyException
     */
    public function revokeRole($role);

    /**
     * @param \Helldar\Roles\Models\Role|string ...$roles
     */
    public function revokeRoles(...$roles);

    /**
     * @param array $roles_ids
     *
     * @throws UnknownModelKeyException
     */
    public function syncRoles(array $roles_ids);
}

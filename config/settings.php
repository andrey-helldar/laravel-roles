<?php

use Helldar\Roles\Models\Permission;
use Helldar\Roles\Models\Role;

return [
    /*
     * Default database connection name.
     *
     * By default, mysql.
     */

    'connection' => env('DB_CONNECTION', 'mysql'),

    /*
     * Table names for models.
     * You can use the default settings or set your own.
     */

    'tables' => [
        'users' => 'users',

        'roles'       => 'roles',
        'permissions' => 'permissions',

        'user_roles'       => 'user_roles',
        'role_permissions' => 'role_permissions',
    ],

    'models' => [
        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * Eloquent model should be used to retrieve your permissions. Of course, it
         * is often just the "Permission" model but you may use whatever you like.
         *
         * The model you want to use as a Permission model needs to implement the
         * `Helldar\Roles\Contracts\Role` contract.
         */

        'role' => Role::class,

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * Eloquent model should be used to retrieve your roles. Of course, it
         * is often just the "Permission" model but you may use whatever you like.
         *
         * The model you want to use as a Permission model needs to implement the
         * `Helldar\Roles\Contracts\Permission` contract.
         */

        'permission' => Permission::class,
    ],

    /*
     * If `true`, then the blade directives `role()` and `permission()` will be specified during initialization.
     *
     * By default, false.
     */

    'use_blade' => false,

    /*
     * If `true`, then you can use `can()` directive in blade files.
     *
     * By default, false.
     */

    'use_can_directive' => false,

    /*
     * Defines for which roles to use root access.
     *
     * Example:
     *     ['admin']
     *     ['admin', 'foo', 'bar']
     *     false
     *
     * Default, false.
     */

    'root_roles' => false,

    /*
     * Determines whether to use the cache to check roles and permissions.
     *
     * Default, false.
     */

    'use_cache' => true,

    'cache_ttl' => 300,
];

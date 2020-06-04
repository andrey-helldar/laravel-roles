<?php

return [
    /*
     * Default database connection name.
     *
     * By default, mysql.
     */

    'connection' => env('DB_CONNECTION', 'mysql'),

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
     * Determines whether to use the cache to check roles and permissions.
     *
     * Default, false.
     */

    'use_cache' => false,

    /*
     * Cache lifetime in seconds.
     *
     * Default, 3600.
     */

    'cache_ttl' => 3600,
];

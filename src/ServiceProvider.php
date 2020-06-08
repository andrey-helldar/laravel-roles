<?php

namespace Helldar\Roles;

use Helldar\Roles\Console\PermissionCreate;
use Helldar\Roles\Console\PermissionDelete;
use Helldar\Roles\Console\RoleCreate;
use Helldar\Roles\Console\RoleDelete;
use Helldar\Roles\Facades\Config;
use Helldar\Roles\Models\Permission;
use Helldar\Roles\Traits\Searchable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    use Searchable;

    public function boot()
    {
        $this->publishConfig();
        $this->publishMigrations();
        $this->bootCommands();

        $this->blade();
        $this->can();
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/roles.php', Config::name());
    }

    protected function publishMigrations()
    {
        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'migrations');
    }

    protected function publishConfig()
    {
        $this->publishes([
            __DIR__ . '/../config/roles.php' => config_path(Config::filename()),
        ], 'config');
    }

    protected function blade()
    {
        if (! Config::useBlade()) {
            return;
        }

        /* Role */
        Blade::directive('role', function ($role) {
            return "<?php if(\auth()->check() && \auth()->user()->hasRole($role)) { ?>";
        });

        Blade::directive('endrole', function () {
            return '<?php } ?>';
        });

        /* Roles */
        Blade::directive('role', function ($roles) {
            return "<?php if(\auth()->check() && \auth()->user()->hasRoles($roles)) { ?>";
        });

        Blade::directive('endroles', function () {
            return '<?php } ?>';
        });

        /* Permission */
        Blade::directive('permission', function ($permission) {
            return "<?php if(\auth()->check() && \auth()->user()->hasPermission($permission)) ?>";
        });

        Blade::directive('endpermission', function () {
            return '<?php } ?>';
        });

        /* Permissions */
        Blade::directive('permissions', function ($permissions) {
            return "<?php if(\auth()->check() && \auth()->user()->hasPermissions($permissions)) ?>";
        });

        Blade::directive('endpermissions', function () {
            return '<?php } ?>';
        });
    }

    protected function can()
    {
        if (! Config::useCanDirective()) {
            return;
        }

        Permission::query()
            ->get(['slug'])
            ->each(function (Permission $permission) {
                Gate::define($permission->slug, function (Authenticatable $user) use ($permission) {
                    return $user->hasPermission($permission);
                });
            });
    }

    protected function bootCommands()
    {
        $this->commands([
            PermissionCreate::class,
            PermissionDelete::class,
            RoleCreate::class,
            RoleDelete::class,
        ]);
    }
}

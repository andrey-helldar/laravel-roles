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
        $this->loadMigrations();
        $this->publishConfig();
        $this->bootCommands();

        $this->blade();
        $this->can();
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/settings.php', Config::name());
    }

    protected function loadMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    protected function publishConfig()
    {
        $this->publishes([
            __DIR__ . '/../config/settings.php' => config_path('laravel_roles.php'),
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
            ->get(['name'])
            ->each(function (Permission $permission) {
                Gate::define($permission->name, function (Authenticatable $user) use ($permission) {
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

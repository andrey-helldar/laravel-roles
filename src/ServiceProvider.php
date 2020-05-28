<?php

namespace Helldar\Roles;

use function config_path;
use Helldar\Roles\Console\PermissionCreate;
use Helldar\Roles\Console\PermissionDelete;
use Helldar\Roles\Console\RoleCreate;
use Helldar\Roles\Console\RoleDelete;
use Helldar\Roles\Exceptions\UnknownModelKeyException;
use Helldar\Roles\Helpers\Config;
use Helldar\Roles\Helpers\Table;
use Helldar\Roles\Models\Permission;
use Helldar\Roles\Traits\Find;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;

use Illuminate\Support\Facades\Schema;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    use Find;

    /**
     * @throws UnknownModelKeyException
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->publishes([
            __DIR__ . '/../config/settings.php' => config_path('laravel_roles.php'),
        ], 'config');

        $this->blade();
        $this->can();

        $this->bootCommands();
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/settings.php', 'laravel_roles');
    }

    protected function blade()
    {
        if (!Config::get('use_blade', false)) {
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

    /**
     * @throws UnknownModelKeyException
     */
    protected function can()
    {
        if (!Config::get('use_can_directive', false)) {
            return;
        }

        $connection = Table::connection();
        $table      = Table::name('permissions');

        if (Schema::connection($connection)->hasTable($table)) {
            /** @var Permission $model */
            $model = $this->model('permission');

            $model::get(['name'])
                ->map(function ($permission) {
                    Gate::define($permission->name, function ($user) use ($permission) {
                        return $user->hasPermission($permission);
                    });
                });
        }
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

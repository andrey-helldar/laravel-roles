<?php

namespace Helldar\Roles;

use Helldar\Roles\Helpers\Config;
use Helldar\Roles\Helpers\Table;
use Helldar\Roles\Models\Permission;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    protected $defer = false;

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        $this->publishes([
            __DIR__ . '/config/settings.php' => \config_path('laravel_roles.php'),
        ], 'config');

        $this->blade();
        $this->can();
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/settings.php', 'laravel_roles');
    }

    private function blade()
    {
        if (!Config::get('use_blade', false)) {
            return;
        }

        Blade::directive('role', function ($role) {
            return "<?php if(\auth()->check() && \auth()->user()->hasRole($role)) { ?>";
        });

        Blade::directive('endrole', function () {
            return '<?php } ?>';
        });

        Blade::directive('permission', function ($permission) {
            return "<?php if(\auth()->check() && \auth()->user()->hasPermission($permission)) ?>";
        });

        Blade::directive('endpermission', function () {
            return '<?php } ?>';
        });
    }

    private function can()
    {
        if (!Config::get('use_can_directive', false)) {
            return;
        }

        $connection = Table::connection();
        $table      = Table::name('permissions');

        if (Schema::connection($connection)->hasTable($table)) {
            Permission::get(['name'])
                ->map(function (Permission $permission) {
                    Gate::define($permission->name, function ($user) use ($permission) {
                        return $user->hasPermission($permission);
                    });
                });
        }
    }
}

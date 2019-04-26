<?php

namespace Helldar\Roles;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Config;

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
}

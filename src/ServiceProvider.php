<?php

namespace Helldar\Roles;

use Illuminate\Support\Facades\Blade;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    protected $defer = false;

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        $this->blade();
    }

    private function blade()
    {
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

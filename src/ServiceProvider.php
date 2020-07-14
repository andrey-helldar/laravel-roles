<?php

namespace Helldar\Roles;

use Helldar\Roles\Console\PermissionCreate;
use Helldar\Roles\Console\PermissionDelete;
use Helldar\Roles\Console\RoleCreate;
use Helldar\Roles\Console\RoleDelete;
use Helldar\Roles\Constants\Tables;
use Helldar\Roles\Facades\Config;
use Helldar\Roles\Models\Permission;
use Helldar\Roles\Traits\Searchable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;

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
        if ($this->doesntExistPermissionsTable() || ! Config::useBlade()) {
            return;
        }

        /* Role */
        Blade::directive('role', function ($role) {
            return "<?php if(\auth()->check() && \auth()->user()->hasRole($role)): ?>";
        });

        Blade::directive('endrole', function () {
            return '<?php endif; ?>';
        });

        /* Roles */
        Blade::directive('roles', function ($roles) {
            return "<?php if(\auth()->check() && \auth()->user()->hasRoles($roles)): ?>";
        });

        Blade::directive('endroles', function () {
            return '<?php endif; ?>';
        });

        /* Permission */
        Blade::directive('permission', function ($permission) {
            return "<?php if(\auth()->check() && \auth()->user()->hasPermission($permission)): ?>";
        });

        Blade::directive('endpermission', function () {
            return '<?php endif; ?>';
        });

        /* Permissions */
        Blade::directive('permissions', function ($permissions) {
            return "<?php if(\auth()->check() && \auth()->user()->hasPermissions($permissions)): ?>";
        });

        Blade::directive('endpermissions', function () {
            return '<?php endif; ?>';
        });
    }

    protected function can()
    {
        if ($this->doesntExistPermissionsTable() || ! Config::useCanDirective()) {
            return;
        }

        foreach ($this->getPermissions() as $permission) {
            Gate::define($permission, function (Authenticatable $user) use ($permission) {
                return $user->hasPermission($permission);
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

    protected function getPermissions(): array
    {
        return Cache::remember('permissions-model', $this->ttl(), static function () {
            return Permission::query()
                ->get(['slug'])
                ->pluck('slug')
                ->toArray();
        });
    }

    protected function doesntExistPermissionsTable(): bool
    {
        return Config::useCache()
            ? Cache::remember(__FUNCTION__, $this->ttl(), function () {
                return ! $this->existPermissionsTable();
            }) : ! $this->existPermissionsTable();
    }

    protected function existPermissionsTable(): bool
    {
        return Schema::connection(Config::connection())->hasTable(Tables::PERMISSIONS);
    }

    protected function ttl(): ?int
    {
        return Config::cacheTtl();
    }
}

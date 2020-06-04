<?php

namespace Tests;

use Helldar\Roles\Facades\Config;
use Helldar\Roles\ServiceProvider;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\database\seeds\TableSeeder;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    use RefreshDatabase;

    private $database = 'testing';

    protected function setUp(): void
    {
        parent::setUp();

        $this->loadLaravelMigrations($this->database);

        $this->refreshDatabase();

        TableSeeder::run();
    }

    protected function getEnvironmentSetUp($app)
    {
        $this->setDatabase($app);
        $this->setRoutes($app);
    }

    protected function getPackageAliases($app)
    {
        return ['Config' => Config::class];
    }

    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }

    protected function resolveApplicationHttpKernel($app)
    {
        $app->singleton(Kernel::class, Http\Kernel::class);
    }

    protected function setCache(bool $allow = false)
    {
        Config::set('use_cache', $allow);
    }

    protected function setDatabase($app)
    {
        $app['config']->set('database.default', $this->database);

        $app['config']->set('database.connections.' . $this->database, [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        Config::set('connection', $this->database);
    }

    protected function setRoutes($app)
    {
        $this->setRoute($app, 'role/access', 'role:foo,bar,baz');
        $this->setRoute($app, 'role/denied', 'role:baz,bax');

        $this->setRoute($app, 'roles/access', 'roles:foo,bar');
        $this->setRoute($app, 'roles/denied', 'roles:foo,bar,baz');

        $this->setRoute($app, 'permission/access', 'permission:foo,bar,baz');
        $this->setRoute($app, 'permission/denied', 'permission:baz,bax');

        $this->setRoute($app, 'permissions/access', 'permissions:foo,bar');
        $this->setRoute($app, 'permissions/denied', 'permissions:foo,bar,baz');

        $this->setRoute($app, 'user/permission/access', 'permission:foo,bax');
        $this->setRoute($app, 'user/permission/denied', 'permission:bar,baz');
    }

    protected function setRoute($app, $url, $middleware)
    {
        $app['router']->get($url, [
            'middleware' => $middleware,
            'uses'       => function () {
                return 'ok';
            },
        ]);
    }
}

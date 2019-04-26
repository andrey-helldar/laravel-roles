<?php

namespace Tests;

use Helldar\Roles\ServiceProvider;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\Facades\Config;
use Tests\database\seeds\TableSeeder;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    private $database = 'testing';

    protected function setUp(): void
    {
        parent::setUp();

        $this->loadLaravelMigrations($this->database);

        $this->artisan('migrate', ['--database' => $this->database])->run();

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
        $app->singleton(Kernel::class, \Tests\Http\Kernel::class);
    }

    private function setDatabase($app)
    {
        $app['config']->set('database.default', $this->database);

        $app['config']->set('database.connections.' . $this->database, [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        $app['config']->set('laravel_roles.connection', $this->database);
    }

    private function setRoutes($app)
    {
        $this->setRoute($app, 'roles/access', 'roles:foo,bar,baz');
        $this->setRoute($app, 'roles/denied', 'roles:foo,bar');

        $this->setRoute($app, 'permissions/access', 'permissions:foo,bar,baz');
        $this->setRoute($app, 'permissions/denied', 'permissions:foo,bar');
    }

    private function setRoute($app, $url, $middleware)
    {
        $app['router']->get($url, [
            'middleware' => $middleware,
            'uses'       => function () {
                return 'ok';
            },
        ]);
    }
}

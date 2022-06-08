<?php

namespace Dealskoo\Thumb\Tests;

use Dealskoo\Thumb\Providers\ThumbServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [ThumbServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('auth.providers.users.model', User::class);
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => ''
        ]);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
    }
}

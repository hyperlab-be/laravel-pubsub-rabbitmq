<?php

namespace Hyperlab\LaravelPubSubRabbitMQ\Tests;

use Hyperlab\LaravelPubSubRabbitMQ\PubSubServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Log::clear();
    }

    protected function getPackageProviders($app)
    {
        return [
            PubSubServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }
}

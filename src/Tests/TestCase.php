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

    protected function tearDown(): void
    {
        Log::clear();

        parent::tearDown();
    }

    protected function getPackageProviders($app)
    {
        return [
            PubSubServiceProvider::class,
        ];
    }
}

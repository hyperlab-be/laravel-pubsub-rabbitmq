<?php

namespace HyperlabBe\LaravelPubSubRabbitMQ;

use HyperlabBe\LaravelPubSubRabbitMQ\Commands\Consume;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelPubSubRabbitMQServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-pubsub-rabbitmq')
            ->hasConfigFile('pubsub')
            ->hasCommand(Consume::class);
    }
}

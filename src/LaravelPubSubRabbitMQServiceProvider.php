<?php

namespace HyperlabBe\LaravelPubSubRabbitMQ;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use HyperlabBe\LaravelPubSubRabbitMQ\Commands\LaravelPubSubRabbitMQCommand;

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
            ->hasCommand(LaravelPubSubRabbitMQCommand::class);
    }
}

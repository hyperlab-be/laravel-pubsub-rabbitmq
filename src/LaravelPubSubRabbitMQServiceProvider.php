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
            ->name('laravel_pubsub_rabbitmq')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel_pubsub_rabbitmq_table')
            ->hasCommand(LaravelPubSubRabbitMQCommand::class);
    }
}

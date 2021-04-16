<?php

namespace Hyperlab\LaravelPubSubRabbitMQ;

use Hyperlab\LaravelPubSubRabbitMQ\Commands\Consume;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class PubSubServiceProvider extends PackageServiceProvider
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

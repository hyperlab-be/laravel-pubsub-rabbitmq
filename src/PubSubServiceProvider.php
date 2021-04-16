<?php

namespace Hyperlab\LaravelPubSubRabbitMQ;

use Hyperlab\LaravelPubSubRabbitMQ\Publisher\MessagePublisher;
use Hyperlab\LaravelPubSubRabbitMQ\Subscriber\Commands\Consume;
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

    public function packageBooted()
    {
        if ($this->app->runningInConsole()) {

            $pathToSubscriptionsFile = config('pubsub.subscriptions');

            $this->publishes([
                $this->package->basePath("/../routes/messages.php") => $pathToSubscriptionsFile,
            ], "{$this->package->shortName()}-subscriptions");

        }

        MessagePublisher::register();
    }
}

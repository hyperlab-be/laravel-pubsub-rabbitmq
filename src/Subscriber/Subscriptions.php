<?php

namespace Hyperlab\LaravelPubSubRabbitMQ\Subscriber;

use Illuminate\Support\Collection;
use Mockery;

class Subscriptions
{
    private Collection $subscriptions;

    public function __construct()
    {
        $pathToSubscriptionsFile = config('pubsub.subscriptions');

        try {
            $subscriptions = require $pathToSubscriptionsFile;
        } catch (\Throwable $exception) {
            throw new \Exception("Could not read the subscriptions from '{$pathToSubscriptionsFile}'.");
        }

        if (! is_array($subscriptions)) {
            throw new \Exception("The subscriptions file should return an array.");
        }

        $this->subscriptions = new Collection($subscriptions);
    }

    public static function new(): self
    {
        return app(self::class);
    }

    public static function partialMock(): Mockery\MockInterface
    {
        $mock = Mockery::mock(self::class)->makePartial();
        app()->bind(self::class, fn () => $mock);

        return $mock;
    }

    public function getMessageTypes(): array
    {
        return $this->subscriptions->keys()->all();
    }

    public function findSubscriberForMessage(Message $message): ?Subscriber
    {
        $subscriber = $this->subscriptions->get($message->getType());

        if ($subscriber === null) {
            return null;
        }

        return new Subscriber($subscriber);
    }
}

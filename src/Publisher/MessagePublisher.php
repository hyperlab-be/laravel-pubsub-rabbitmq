<?php

namespace Hyperlab\LaravelPubSubRabbitMQ\Publisher;

use Hyperlab\LaravelPubSubRabbitMQ\Message;
use Illuminate\Queue\QueueManager;
use Illuminate\Support\Facades\Event;

class MessagePublisher
{
    public static function register(): void
    {
        Event::listen('*', function (string $eventName, array $data) {
            $event = $data[0];

            if (! $event instanceof ShouldPublish) {
                return;
            }

            $message = new Message($event->publishAs(), $event->publishWith());

            /** @var QueueManager $queueManager */
            $queueManager = app('queue');
            $queueConnection = config('pubsub.queue.connection');

            $queueManager->connection($queueConnection)->pushRaw($message->serialize(), $event->publishAs());
        });
    }
}

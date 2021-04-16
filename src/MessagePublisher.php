<?php

namespace Hyperlab\LaravelPubSubRabbitMQ;

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

            $payload = json_encode([
                'type' => $event->publishAs(),
                'data' => $event->publishWith(),
            ]);

            /** @var QueueManager $queueManager */
            $queueManager = app('queue');
            $queueConnection = config('pubsub.queue.connection');

            $queueManager->connection($queueConnection)->pushRaw($payload, $event->publishAs());
        });
    }
}

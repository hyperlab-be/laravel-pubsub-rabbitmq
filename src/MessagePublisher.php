<?php

namespace HyperlabBe\LaravelPubSubRabbitMQ;

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

            $queueManager->connection('rabbitmq')->pushRaw($payload, $event->publishAs());
        });
    }
}

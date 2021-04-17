<?php

namespace Hyperlab\LaravelPubSubRabbitMQ\Publisher;

use Hyperlab\LaravelPubSubRabbitMQ\PubSubConnector;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;

class MessagePublisher
{
    public static function register(): void
    {
        Event::listen('*', function (string $eventName, array $data) {
            $event = $data[0];

            if (! $event instanceof ShouldPublish) {
                return;
            }

            $config = config('queue.connections.'.config('pubsub.queue.connection'));

            $data = json_encode([
                'id' => Str::uuid(),
                'type' => $event->publishAs(),
                'payload' => $event->publishWith(),
                'published_at' => now()->toIso8601String(),
            ]);

            PubSubConnector::new()
                ->withDefaultWorker()
                ->connect($config)
                ->pushRaw($data, $event->publishAs());
        });
    }
}

<?php

namespace Hyperlab\LaravelPubSubRabbitMQ\Publisher;

use Hyperlab\LaravelPubSubRabbitMQ\Message;
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

            Message::new($event->publishAs(), $event->publishWith())->publish();
        });
    }
}

<?php

namespace Hyperlab\LaravelPubSubRabbitMQ\Subscriber;

use Hyperlab\LaravelPubSubRabbitMQ\Message;

class Subscriber
{
    public function __construct(string | array $subscriber)
    {
    }

    public function handle(Message $message): void
    {
        // TODO: handle message
    }
}

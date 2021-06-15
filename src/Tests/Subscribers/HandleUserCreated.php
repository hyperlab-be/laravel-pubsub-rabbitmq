<?php

namespace Hyperlab\LaravelPubSubRabbitMQ\Tests\Subscribers;

use Hyperlab\LaravelPubSubRabbitMQ\Subscriber\Message;
use Hyperlab\LaravelPubSubRabbitMQ\Tests\Log;

class HandleUserCreated
{
    public function __invoke(Message $message): void
    {
        $firstName = $message->getPayload()->get('first_name');

        Log::write("Welcome, {$firstName}!");
    }
}

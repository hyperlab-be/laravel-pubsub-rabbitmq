<?php

namespace Hyperlab\LaravelPubSubRabbitMQ\Tests\Subscribers;

use Hyperlab\LaravelPubSubRabbitMQ\Message;
use Hyperlab\LaravelPubSubRabbitMQ\Tests\Log;

class HandleUserCreated
{
    public function __invoke(Message $message): void
    {
        $firstName = $message->getPayload()['first_name'];

        Log::write("Welcome, {$firstName}!");
    }
}
<?php

namespace Hyperlab\LaravelPubSubRabbitMQ\Tests\Subscribers;

use Hyperlab\LaravelPubSubRabbitMQ\Subscriber\Message;
use Hyperlab\LaravelPubSubRabbitMQ\Tests\Log;

class HandleUserDeleted
{
    public function handle(Message $message): void
    {
        $email = $message->getPayload()->get('email');

        Log::write("Goodbye email sent to {$email}");
    }
}

<?php

namespace Hyperlab\LaravelPubSubRabbitMQ;

use VladimirYuldashev\LaravelQueueRabbitMQ\Queue\Jobs\RabbitMQJob;

class Message extends RabbitMQJob
{
    public function getName(): string
    {
        $type = $this->payload()['type'];

        return "Handle message: {$type}";
    }

    public function fire(): void
    {
        $this->payload();

        // TODO: handle message

        $this->delete();
    }

    protected function failed($e)
    {
        $this->release();
    }
}

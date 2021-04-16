<?php

namespace Hyperlab\LaravelPubSubRabbitMQ\Subscriber;

use Hyperlab\LaravelPubSubRabbitMQ\Message;
use VladimirYuldashev\LaravelQueueRabbitMQ\Queue\Jobs\RabbitMQJob as BaseJob;

class RabbitMQJob extends BaseJob
{
    public function getName(): string
    {
        $type = $this->payload()['type'];

        return "Handle message: {$type}";
    }

    public function fire(): void
    {
        $message = Message::deserialize($this->payload());

        $subscriber = Subscriptions::new()->findSubscriberForMessageType($message->getType());

        if($subscriber !== null) {
            $subscriber->handle($message);
        }

        $this->delete();
    }

    protected function failed($e)
    {
        $this->release();
    }
}

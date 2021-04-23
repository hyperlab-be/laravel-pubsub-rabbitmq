<?php

namespace Hyperlab\LaravelPubSubRabbitMQ\Subscriber;

use VladimirYuldashev\LaravelQueueRabbitMQ\Queue\Jobs\RabbitMQJob;

class QueueJob extends RabbitMQJob
{
    public function getName(): string
    {
        $type = $this->payload()['type'];

        return "Handle message: {$type}";
    }

    public function fire(): void
    {
        $message = Message::deserialize($this->payload());

        $subscriber = Subscriptions::new()->findSubscriberForMessage($message);

        if (config('pubsub.queue.worker') === 'horizon') {
            Horizon::new()->jobPushed($this);
        }

        if ($subscriber !== null) {
            $subscriber->handle($message);
        }

        $this->delete();
    }

    protected function failed($e)
    {
        $this->release();
    }
}

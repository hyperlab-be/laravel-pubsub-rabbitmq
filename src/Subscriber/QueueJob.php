<?php

namespace Hyperlab\LaravelPubSubRabbitMQ\Subscriber;

use Illuminate\Contracts\Events\Dispatcher;
use Laravel\Horizon\Events\JobPushed;
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

        if ($subscriber !== null) {
            $subscriber->handle($message);
        }

        if(config('pubsub.queue.worker') === 'horizon') {
            $this->fireHorizonEvent();
        }

        $this->delete();
    }

    protected function fireHorizonEvent(): void
    {
        $eventPayload = $this->payload();
        $eventPayload['data']['commandName'] = 'command name';
        $eventPayload['displayName'] = 'display name';
        $eventPayload = json_encode($eventPayload);

        $event = (new JobPushed($eventPayload))
            ->connection($this->connectionName)
            ->queue($this->queue);

        app(Dispatcher::class)->dispatch($event);
    }

    protected function failed($e)
    {
        $this->release();
    }
}

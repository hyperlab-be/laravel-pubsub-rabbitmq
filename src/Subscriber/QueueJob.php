<?php

namespace Hyperlab\LaravelPubSubRabbitMQ\Subscriber;

use Illuminate\Contracts\Events\Dispatcher;
use VladimirYuldashev\LaravelQueueRabbitMQ\Queue\Jobs\RabbitMQJob;
use Laravel\Horizon\Events\JobPushed;

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
            $this->fireHorizonEvent($message);
        }

        if ($subscriber !== null) {
            $subscriber->handle($message);
        }

        $this->delete();
    }

    protected function fireHorizonEvent(Message $message): void
    {
        $eventPayload = json_encode([
            "id" => $message->getId(),
            "uuid" => $message->getId(),
            "displayName" => "Handle pub/sub message",
            "data" => [
                'command' => serialize($this->payload()),
                'commandName' => $message->getType(),
            ],
            "pushedAt" => $message->getPublishedAt()->timestamp,
            "tags" => [
                $message->getType(),
            ],
            "job" => self::class."@call",
            "type" => "job",
        ]);

        $event = (new JobPushed($eventPayload))->connection($this->connectionName)->queue($this->queue);

        app(Dispatcher::class)->dispatch($event);
    }

    protected function failed($e)
    {
        $this->release();
    }
}

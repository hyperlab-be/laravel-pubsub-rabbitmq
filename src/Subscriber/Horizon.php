<?php

namespace Hyperlab\LaravelPubSubRabbitMQ\Subscriber;

use Illuminate\Contracts\Events\Dispatcher;
use Laravel\Horizon\Events\JobPushed;
use Laravel\Horizon\Events\JobFailed;
use Laravel\Horizon\Events\RedisEvent;
use Throwable;
use VladimirYuldashev\LaravelQueueRabbitMQ\Queue\Jobs\RabbitMQJob;

class Horizon
{
    public static function new(): static
    {
        return new static;
    }

    public function jobPushed(RabbitMQJob $job): void
    {
        $eventPayload = $this->generateHorizonEventPayload($job);

        $event = new JobPushed($eventPayload);

        $this->fireEvent($job, $event);
    }

    public function jobFailed(Throwable $exception, RabbitMQJob $job): void
    {
        $eventPayload = $this->generateHorizonEventPayload($job);

        $event = new JobFailed($exception, $job, $eventPayload);

        $this->fireEvent($job, $event);
    }

    private function generateHorizonEventPayload(RabbitMQJob $job): string
    {
        $message = Message::deserialize($job->payload());

        return json_encode([
            "id" => $message->getId(),
            "uuid" => $message->getId(),
            "displayName" => "Handle pub/sub message",
            "data" => [
                'command' => serialize($job->payload()),
                'commandName' => $message->getType(),
            ],
            "pushedAt" => $message->getPublishedAt()->timestamp,
            "tags" => [
                $message->getType(),
            ],
            "job" => self::class . "@call",
            "type" => "job",
        ]);
    }

    private function fireEvent(RabbitMQJob $job, RedisEvent $event): void
    {
        $event->connection($job->getConnectionName())->queue($job->getQueue());

        app(Dispatcher::class)->dispatch($event);
    }
}

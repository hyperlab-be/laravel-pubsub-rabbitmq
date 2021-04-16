<?php

namespace Hyperlab\LaravelPubSubRabbitMQ;

use Illuminate\Queue\QueueManager;

class Message
{
    public function __construct(private string $type, private array $payload)
    {
    }

    public static function new(string $type, array $payload): static
    {
        return new static($type, $payload);
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }

    public function publish(): void
    {
        /** @var QueueManager $queueManager */
        $queueManager = app('queue');
        $queueConnection = config('pubsub.queue.connection');

        $queueManager->connection($queueConnection)->pushRaw($this->serialize(), $this->type);
    }

    private function serialize(): string
    {
        return json_encode([
            'type' => $this->type,
            'payload' => $this->payload,
        ]);
    }

    public static function deserialize(array $serialization): static
    {
        return new static($serialization['type'], $serialization['payload']);
    }
}

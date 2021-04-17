<?php

namespace Hyperlab\LaravelPubSubRabbitMQ\Subscriber;

class Message
{
    public function __construct(private string $type, private array $payload)
    {
    }

    public static function new(string $type, array $payload): static
    {
        return new static($type, $payload);
    }

    public static function deserialize(array $serialization): static
    {
        return new static($serialization['type'], $serialization['payload']);
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }
}

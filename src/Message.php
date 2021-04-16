<?php

namespace Hyperlab\LaravelPubSubRabbitMQ;

class Message
{
    public function __construct(private string $type, private array $payload) {}

    public function getType(): string
    {
        return $this->type;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }

    public function serialize(): string
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

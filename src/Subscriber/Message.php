<?php

namespace Hyperlab\LaravelPubSubRabbitMQ\Subscriber;

use Illuminate\Support\Carbon;

class Message
{
    public function __construct(
        private string $id,
        private string $type,
        private array $payload,
        private Carbon $publishedAt
    ) {
    }

    public static function new(string $id, string $type, array $payload, Carbon $publishedAt): static
    {
        return new static($id, $type, $payload, $publishedAt);
    }

    public static function deserialize(array $serialization): static
    {
        $publishedAt = Carbon::parse($serialization['published_at']);

        return new static($serialization['id'], $serialization['type'], $serialization['payload'], $publishedAt);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }

    public function getPublishedAt(): Carbon
    {
        return $this->publishedAt;
    }
}

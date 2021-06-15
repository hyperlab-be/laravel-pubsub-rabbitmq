<?php

namespace Hyperlab\LaravelPubSubRabbitMQ\Subscriber;

use Illuminate\Support\Carbon;

class Message
{
    public function __construct(
        private string $id,
        private string $type,
        private MessagePayload $payload,
        private Carbon $publishedAt
    ) {
    }

    public static function new(string $id, string $type, MessagePayload $payload, Carbon $publishedAt): static
    {
        return new static($id, $type, $payload, $publishedAt);
    }

    public static function deserialize(array $serialization): static
    {
        $publishedAt = Carbon::parse($serialization['published_at']);

        $payload = MessagePayload::new($serialization['payload']);

        return new static($serialization['id'], $serialization['type'], $payload, $publishedAt);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getPayload(): MessagePayload
    {
        return $this->payload;
    }

    public function getPublishedAt(): Carbon
    {
        return $this->publishedAt;
    }
}

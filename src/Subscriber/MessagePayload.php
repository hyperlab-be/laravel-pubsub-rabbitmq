<?php

namespace Hyperlab\LaravelPubSubRabbitMQ\Subscriber;

use Adbar\Dot;

class MessagePayload extends Dot
{
    public static function new(array $items = []): self
    {
        return new self($items);
    }
}

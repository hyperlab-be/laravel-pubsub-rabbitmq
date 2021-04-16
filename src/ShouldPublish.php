<?php

namespace Hyperlab\LaravelPubSubRabbitMQ;

interface ShouldPublish
{
    public function publishAs(): string;

    public function publishWith(): array;
}

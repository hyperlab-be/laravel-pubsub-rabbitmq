<?php

namespace Hyperlab\LaravelPubSubRabbitMQ\Publisher;

interface ShouldPublish
{
    public function publishAs(): string;

    public function publishWith(): array;
}

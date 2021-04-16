<?php

namespace HyperlabBe\LaravelPubSubRabbitMQ;

interface ShouldPublish
{
    public function publishAs(): string;

    public function publishWith(): array;
}

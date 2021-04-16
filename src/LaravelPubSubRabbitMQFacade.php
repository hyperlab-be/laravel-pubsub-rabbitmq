<?php

namespace HyperlabBe\LaravelPubSubRabbitMQ;

use Illuminate\Support\Facades\Facade;

/**
 * @see \HyperlabBe\LaravelPubSubRabbitMQ\LaravelPubSubRabbitMQ
 */
class LaravelPubSubRabbitMQFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel_pubsub_rabbitmq';
    }
}

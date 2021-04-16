<?php

use Hyperlab\LaravelPubSubRabbitMQ\Tests\Subscribers\HandleUserCreated;
use Hyperlab\LaravelPubSubRabbitMQ\Tests\Subscribers\HandleUserDeleted;

return [

    'user.created' => HandleUserCreated::class,

    'user.deleted' => [HandleUserDeleted::class, 'handle'],

];

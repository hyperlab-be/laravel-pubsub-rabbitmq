<?php

namespace Hyperlab\LaravelPubSubRabbitMQ\Subscriber\Commands;

use Hyperlab\LaravelPubSubRabbitMQ\Subscriber\Subscriptions;
use Hyperlab\LaravelPubSubRabbitMQ\Tests\TestCase;
use Illuminate\Support\Facades\Artisan;

class RegisterTest extends TestCase
{
    /** @test */
    public function the_correct_artisan_commands_are_called_when_the_register_command_is_executed()
    {
        config()->set('pubsub.queue.connection', 'rabbitmq');
        config()->set('pubsub.rabbitmq.exchange', 'application-x');
        config()->set('pubsub.rabbitmq.queue', 'notifications');

        Subscriptions::partialMock()
            ->shouldReceive('getMessageTypes')
            ->andReturn(['user.created', 'user.deleted']);

        $mock = Artisan::partialMock();

        $mock
            ->shouldReceive('call')
            ->once()
            ->withSomeOfArgs('rabbitmq:exchange-declare application-x rabbitmq --type topic');

        $mock
            ->shouldReceive('call')
            ->once()
            ->withSomeOfArgs('rabbitmq:queue-declare notifications rabbitmq');

        $mock
            ->shouldReceive('call')
            ->once()
            ->withSomeOfArgs('rabbitmq:queue-bind notifications application-x rabbitmq --routing-key user.created');

        $mock
            ->shouldReceive('call')
            ->once()
            ->withSomeOfArgs('rabbitmq:queue-bind notifications application-x rabbitmq --routing-key user.deleted');

        (new Register)->handle();
    }
}

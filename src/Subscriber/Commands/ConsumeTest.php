<?php

namespace Hyperlab\LaravelPubSubRabbitMQ\Subscriber\Commands;

use Hyperlab\LaravelPubSubRabbitMQ\Subscriber\Subscriptions;
use Hyperlab\LaravelPubSubRabbitMQ\Tests\TestCase;
use Illuminate\Support\Facades\Artisan;

class ConsumeTest extends TestCase
{
    /** @test */
    public function the_correct_artisan_commands_are_called_when_the_consume_command_is_executed()
    {
        config()->set('pubsub.queue.connection', 'rabbitmq');
        config()->set('pubsub.rabbitmq.queue', 'notifications');

        Subscriptions::partialMock()
            ->shouldReceive('getMessageTypes')
            ->andReturn(['user.created', 'user.deleted']);

        $mock = Artisan::partialMock();

        $mock
            ->shouldReceive('call')
            ->once()
            ->withSomeOfArgs('pubsub:register');

        $mock
            ->shouldReceive('call')
            ->once()
            ->withSomeOfArgs('rabbitmq:consume rabbitmq --name notifications --queue notifications');

        (new Consume)->handle();
    }
}

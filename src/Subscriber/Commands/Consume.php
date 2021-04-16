<?php

namespace Hyperlab\LaravelPubSubRabbitMQ\Subscriber\Commands;

use Hyperlab\LaravelPubSubRabbitMQ\Subscriber\Subscriptions;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Output\ConsoleOutput;

class Consume extends Command
{
    public $signature = 'pubsub:consume';

    public $description = 'Consume the messages from the message queue.';

    public function handle()
    {
        $queueConnection = config('pubsub.queue.connection');
        $exchange = config('pubsub.rabbitmq.exchange');
        $queue = config('pubsub.rabbitmq.queue');

        $this->declareExchange($queueConnection, $exchange);
        $this->declareQueue($queueConnection, $queue);
        $this->declareQueueBindings($queueConnection, $exchange, $queue);
        $this->startConsumer($queueConnection, $queue);
    }

    private function declareExchange(string $queueConnection, string $exchange): void
    {
        $this->artisan("rabbitmq:exchange-declare {$exchange} {$queueConnection} --type topic");
    }

    private function declareQueue(string $queueConnection, string $queue): void
    {
        $this->artisan("rabbitmq:queue-declare {$queue} {$queueConnection}");
    }

    private function declareQueueBindings(string $queueConnection, string $exchange, string $queue): void
    {
        $routingKeys = Subscriptions::new()->getMessageTypes();

        foreach ($routingKeys as $routingKey) {
            $this->artisan("rabbitmq:queue-bind {$queue} {$exchange} {$queueConnection} --routing-key {$routingKey}");
        }
    }

    private function startConsumer(string $queueConnection, string $queue): void
    {
        $this->artisan("rabbitmq:consume {$queueConnection} --name {$queue} --queue {$queue}");
    }

    private function artisan(string $command): void
    {
        Artisan::call($command, [], new ConsoleOutput);
    }
}

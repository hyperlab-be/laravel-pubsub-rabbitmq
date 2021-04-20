<?php

namespace Hyperlab\LaravelPubSubRabbitMQ\Subscriber\Commands;

use Hyperlab\LaravelPubSubRabbitMQ\PubSubConnector;
use Hyperlab\LaravelPubSubRabbitMQ\Subscriber\Subscriptions;
use Illuminate\Console\Command;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use VladimirYuldashev\LaravelQueueRabbitMQ\Queue\RabbitMQQueue;

class Register extends Command
{
    public $signature = 'pubsub:register';

    public $description = 'Register the consumer with RabbitMQ.';

    public function handle(): void
    {
        $exchange = config('pubsub.rabbitmq.exchange');
        $queue = config('pubsub.rabbitmq.queue');

        $config = config('queue.connections.'.config('pubsub.queue.connection'));
        $connection = PubSubConnector::new()->connect($config);

        $this->declareExchange($connection, $exchange);
        $this->declareQueue($connection, $queue);
        $this->declareQueueBindings($connection, $exchange, $queue);
    }

    private function declareExchange(RabbitMQQueue $connection, string $exchange): void
    {
        if ($connection->isExchangeExists($exchange)) {
            $this->warn('Exchange already exists.');

            return;
        }

        $connection->declareExchange($exchange, AMQPExchangeType::TOPIC);

        $this->info('Exchange declared successfully.');
    }

    private function declareQueue(RabbitMQQueue $connection, string $queue): void
    {
        if ($connection->isQueueExists($queue)) {
            $this->warn('Queue already exists.');

            return;
        }

        $connection->declareQueue($queue);

        $this->info('Queue declared successfully.');
    }

    private function declareQueueBindings(RabbitMQQueue $connection, string $exchange, string $queue): void
    {
        $routingKeys = Subscriptions::new()->getMessageTypes();

        foreach ($routingKeys as $routingKey) {
            $connection->bindQueue($queue, $exchange, $routingKey);
            $this->info('Queue bound to exchange successfully.');
        }
    }
}

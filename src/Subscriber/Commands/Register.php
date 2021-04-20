<?php

namespace Hyperlab\LaravelPubSubRabbitMQ\Subscriber\Commands;

use Hyperlab\LaravelPubSubRabbitMQ\PubSubConnector;
use Hyperlab\LaravelPubSubRabbitMQ\Subscriber\Subscriptions;
use Illuminate\Console\Command;
use PhpAmqpLib\Exception\AMQPIOException;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use VladimirYuldashev\LaravelQueueRabbitMQ\Queue\RabbitMQQueue;

class Register extends Command
{
    public $signature = '
        pubsub:register
        {--timeout=60 : Time in seconds that connecting should be attempted}
    ';

    public $description = 'Register the consumer with RabbitMQ.';

    public function handle(): void
    {
        $exchange = config('pubsub.rabbitmq.exchange');
        $queue = config('pubsub.rabbitmq.queue');

        $connection = $this->connectToRabbitMQ();
        $this->declareExchange($connection, $exchange);
        $this->declareQueue($connection, $queue);
        $this->declareQueueBindings($connection, $exchange, $queue);
    }

    private function connectToRabbitMQ(): RabbitMQQueue
    {
        $config = config('queue.connections.'.config('pubsub.queue.connection'));
        $connector = PubSubConnector::new();

        $this->info('Waiting for a successful connection...');

        $timeout = $this->getTimeout();
        $this->output->progressStart($timeout);

        do {
            try {
                $connection = $connector->connect($config);
            } catch (AMQPIOException $e) {
                if ($e->getCode() !== 111 || $timeout <= 0) {
                    throw $e;
                }
                $timeout--;
                sleep(1);
                $this->output->progressAdvance();
            }
        } while (! isset($connection));

        $this->output->progressFinish();

        $this->info('Successfully established a connection.');

        return $connection;
    }

    private function getTimeout(): int
    {
        $timeout = $this->option('timeout') ?? 60;

        if (! is_numeric($timeout)) {
            throw new \InvalidArgumentException('Must pass an integer to option: timeout');
        }

        return (int) $timeout;
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

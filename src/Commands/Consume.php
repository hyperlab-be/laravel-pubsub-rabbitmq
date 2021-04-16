<?php

namespace Hyperlab\LaravelPubSubRabbitMQ\Commands;

use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Output\ConsoleOutput;

class Consume
{
    public string $signature = 'pubsub:consume';

    public string $description = 'Consume the messages from the message queue.';

    public function handle()
    {
        $queueConnection = config('pubsub.queue.connection');
        $exchange = config('pubsub.rabbitmq.exchange');
        $queue = config('pubsub.rabbitmq.queue');
        $routingKeys = $this->getRoutingKeys();

        $this->call("rabbitmq:exchange-declare {$exchange} {$queueConnection} --type topic");
        $this->call("rabbitmq:queue-declare {$queue} {$queueConnection}");

        foreach ($routingKeys as $routingKey) {
            $this->call("rabbitmq:queue-bind {$queue} {$exchange} {$queueConnection} --routing-key {$routingKey}");
        }

        $this->call("rabbitmq:consume {$queueConnection} --name {$queue} --queue {$queue}");
    }

    private function getRoutingKeys(): array
    {
        $pathToSubscriptionsFile = config('pubsub.subscriptions');

        try {
            $subscriptions = require $pathToSubscriptionsFile;
        } catch (\Throwable $exception) {
            throw new \Exception("Could not read the subscriptions from '{$pathToSubscriptionsFile}'.");
        }

        return array_keys($subscriptions);
    }

    private function call(string $command): void
    {
        Artisan::call($command, [], new ConsoleOutput);
    }
}

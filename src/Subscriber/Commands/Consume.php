<?php

namespace Hyperlab\LaravelPubSubRabbitMQ\Subscriber\Commands;

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
        $queue = config('pubsub.rabbitmq.queue');

        $this->artisan("pubsub:register");
        $this->artisan("rabbitmq:consume {$queueConnection} --name {$queue} --queue {$queue}");
    }

    private function artisan(string $command): void
    {
        Artisan::call($command, [], new ConsoleOutput);
    }
}

<?php

namespace HyperlabBe\LaravelPubSubRabbitMQ\Commands;

use Illuminate\Console\Command;

class LaravelPubSubRabbitMQCommand extends Command
{
    public $signature = 'laravel_pubsub_rabbitmq';

    public $description = 'My command';

    public function handle()
    {
        $this->comment('All done');
    }
}

<?php

namespace HyperlabBe\LaravelPubSubRabbitMQ\Commands;

use Illuminate\Console\Command;

class LaravelPubSubRabbitMQCommand extends Command
{
    public $signature = 'pubsub';

    public $description = 'My command';

    public function handle()
    {
        $this->comment('All done');
    }
}

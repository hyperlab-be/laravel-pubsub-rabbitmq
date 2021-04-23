<?php

namespace Hyperlab\LaravelPubSubRabbitMQ\Subscriber;

use Illuminate\Queue\Events\JobFailed;
use VladimirYuldashev\LaravelQueueRabbitMQ\Queue\Jobs\RabbitMQJob;

class QueueJobFailedEvent
{
    public function handle(JobFailed $event): void
    {
        if (! $event->job instanceof RabbitMQJob) {
            return;
        }

        Horizon::new()->jobFailed($event->exception, $event->job);
    }
}

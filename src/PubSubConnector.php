<?php

namespace Hyperlab\LaravelPubSubRabbitMQ;

use Exception;
use Hyperlab\LaravelPubSubRabbitMQ\Subscriber\QueueJob;
use Hyperlab\LaravelPubSubRabbitMQ\Subscriber\QueueJobFailedEvent;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Queue\Queue;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\WorkerStopping;
use Illuminate\Queue\QueueManager;
use PhpAmqpLib\Connection\AbstractConnection;
use PhpAmqpLib\Connection\AMQPLazyConnection;
use VladimirYuldashev\LaravelQueueRabbitMQ\Horizon\RabbitMQQueue as HorizonRabbitMQQueue;
use VladimirYuldashev\LaravelQueueRabbitMQ\Queue\Connectors\RabbitMQConnector;
use VladimirYuldashev\LaravelQueueRabbitMQ\Queue\RabbitMQQueue;

class PubSubConnector extends RabbitMQConnector
{
    private Dispatcher $dispatcher;
    private ?string $workerType;

    public function __construct()
    {
        $this->dispatcher = app(Dispatcher::class);
        parent::__construct($this->dispatcher);
    }

    public static function new(): static
    {
        return app(self::class);
    }

    public static function register(): void
    {
        /** @var QueueManager $queue */
        $queue = app('queue');

        $queue->addConnector('pubsub', fn () => new static);
    }

    public function withDefaultWorker(): static
    {
        $this->workerType = 'default';

        return $this;
    }

    /**
     * Establish a queue connection.
     *
     * @param array $config
     *
     * @return RabbitMQQueue
     * @throws Exception
     */
    public function connect(array $config): Queue
    {
        $connection = $this->createConnection($config);
        $queue = $this->createRabbitQueue($connection);

        if ($queue instanceof HorizonRabbitMQQueue) {
            $this->dispatcher->listen(JobFailed::class, QueueJobFailedEvent::class);
        }

        $this->dispatcher->listen(WorkerStopping::class, static fn () => $queue->close());

        return $queue;
    }

    protected function createConnection(array $config): AbstractConnection
    {
        $config['connection'] = AMQPLazyConnection::class;

        return parent::createConnection($config);
    }

    private function createRabbitQueue(AbstractConnection $connection): RabbitMQQueue
    {
        $worker = $this->workerType ?? config('pubsub.queue.worker', 'default');

        $queue = config('pubsub.rabbitmq.queue');

        $options = [
            'job' => QueueJob::class,
            'exchange' => config('pubsub.rabbitmq.exchange'),
            'exchange_type' => 'topic',
        ];

        return match ($worker) {
            'default' => new RabbitMQQueue($connection, $queue, $options),
            'horizon' => new HorizonRabbitMQQueue($connection, $queue, $options),
        };
    }
}

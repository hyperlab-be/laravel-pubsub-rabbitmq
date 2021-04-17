<?php

return [

    'rabbitmq' => [

        /*
         * The name of the RabbitMQ exchange on which the outgoing messages are published.
         */
        'exchange' => env('PUBSUB_RABBITMQ_EXCHANGE'),

        /*
         * The name of the RabbitMQ queue from which the incoming messages are consumed.
         */
        'queue' => env('PUBSUB_RABBITMQ_QUEUE'),

    ],

    'queue' => [

        /*
         * The queue connection that is used to communicate with RabbitMQ.
         */
        'connection' => env('PUBSUB_QUEUE_CONNECTION', 'pubsub'),

        /*
         * The queue worker that you want to consume your incoming messages with.
         *
         * Valid options are: default, horizon
         */
        'worker' => env('PUBSUB_QUEUE_WORKER', 'default'),

    ],

    /*
     * The file within your code base that contains the message subscriptions of your application.
     */
    'subscriptions' => base_path('routes/subscriptions.php'),

];

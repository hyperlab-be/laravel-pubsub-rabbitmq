<?php

return [

    'rabbitmq' => [
        'exchange' => env('PUBSUB_RABBITMQ_EXCHANGE'),
        'queue' => env('PUBSUB_RABBITMQ_QUEUE'),
    ],

    'queue' => [
        'connection' => env('PUBSUB_QUEUE_CONNECTION', 'rabbitmq'),
    ],

];

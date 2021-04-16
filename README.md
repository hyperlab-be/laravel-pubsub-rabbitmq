# Laravel Pub/Sub RabbitMQ

[![Latest Version on Packagist](https://img.shields.io/packagist/v/hyperlab/laravel-pubsub-rabbitmq.svg?style=flat-square)](https://packagist.org/packages/hyperlab/laravel-pubsub-rabbitmq)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/hyperlab-be/laravel-pubsub-rabbitmq/run-tests?label=tests)](https://github.com/hyperlab-be/laravel-pubsub-rabbitmq/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/hyperlab-be/laravel-pubsub-rabbitmq/Check%20&%20fix%20styling?label=code%20style)](https://github.com/hyperlab-be/laravel-pubsub-rabbitmq/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/hyperlab/laravel-pubsub-rabbitmq.svg?style=flat-square)](https://packagist.org/packages/hyperlab/laravel-pubsub-rabbitmq)

This package provides an opinionated approach to implement Pub/Sub messaging in Laravel using RabbitMQ.

## Installation

Install the package via composer:

```bash
composer require hyperlab/laravel-pubsub-rabbitmq
```

Publish the `config/pubsub.php` config file:

```bash
php artisan vendor:publish --provider="Hyperlab\LaravelPubSubRabbitMQ\PubSubServiceProvider" --tag="pubsub-rabbitmq-config"
```

Publish the `routes/subscriptions.php` routes file:

```bash
php artisan vendor:publish --provider="Hyperlab\LaravelPubSubRabbitMQ\PubSubServiceProvider" --tag="pubsub-rabbitmq-subscriptions"
```

## Configuration

You can configure the package to suit your needs through the `config/pubsub.php` config file. By default, the file looks like this:

```php
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
        'connection' => env('PUBSUB_QUEUE_CONNECTION', 'rabbitmq'),

    ],

    /*
     * The file within your code base that contains the message subscriptions of your application.
     */
    'subscriptions' => base_path('routes/subscriptions.php'),

];

```

## Subscribers

In order for your application to receive messages from RabbitMQ, it needs to subscribe to one or more message types.

By default, these subscriptions can be defined in the `routes/subscriptions.php` file. However, the path to this file can
be changed in the [configuration file](#configuration).

The contents of the subscriptions file should look like this:

```php
<?php

return [

    'user.created' => HandleUserCreatedMessage::class,

    'user.deleted' => [HandleUserDeletedMessage::class, 'handle'],

];
```

The file returns an associative array in which:
- **a key** represents a type of messages that the application wants to receive
- **a value** represents the class within your application that handles the incoming message of this type

The handler of a subscription can be defined in two ways:

1. By referencing a class

    ```php
    'user.created' => HandleUserCreatedMessage::class,
    ```
    
    In this case, the package looks for a public method in the class that accepts a `Hyperlab\LaravelPubSubRabbitMQ\Message`
    as argument. This method can be called anything, as shown here:
    
    ```php
    class HandleUserCreatedMessage
    {
        public function __invoke(Hyperlab\LaravelPubSubRabbitMQ\Message $message): void
        {
            //
        }
    }
    
    class HandleUserCreatedMessage
    {
        public function handle(Hyperlab\LaravelPubSubRabbitMQ\Message $message): void
        {
            //
        }
    }
    
    class HandleUserCreatedMessage
    {
        public function execute(Hyperlab\LaravelPubSubRabbitMQ\Message $message): void
        {
            //
        }
    }
    ```

2. By referencing a class and method

    ```php
    'user.created' => [HandleUserCreatedMessage::class, 'handle'],
    ```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Hyperlab](https://hyperlab.be)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

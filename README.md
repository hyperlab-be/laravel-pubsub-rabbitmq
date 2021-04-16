# Laravel Pub/Sub RabbitMQ

[![Latest Version on Packagist](https://img.shields.io/packagist/v/hyperlab/laravel-pubsub-rabbitmq.svg?style=flat-square)](https://packagist.org/packages/hyperlab/laravel-pubsub-rabbitmq)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/hyperlab-be/laravel-pubsub-rabbitmq/run-tests?label=tests)](https://github.com/hyperlab-be/laravel-pubsub-rabbitmq/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/hyperlab-be/laravel-pubsub-rabbitmq/Check%20&%20fix%20styling?label=code%20style)](https://github.com/hyperlab-be/laravel-pubsub-rabbitmq/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/hyperlab/laravel-pubsub-rabbitmq.svg?style=flat-square)](https://packagist.org/packages/hyperlab/laravel-pubsub-rabbitmq)

This package provides an opinionated approach to implement Pub/Sub messaging in Laravel using RabbitMQ.

## Installation

You can install the package via composer:

```bash
composer require hyperlab/laravel-pubsub-rabbitmq
```

You can publish the config file with:

```bash
php artisan vendor:publish --provider="Hyperlab\LaravelPubSubRabbitMQ\PubSubServiceProvider" --tag="pubsub-rabbitmq-config"
```

This is the contents of the published config file:

```php
return [
    //
];
```

You can publish the subscriptions file with:

```bash
php artisan vendor:publish --provider="Hyperlab\LaravelPubSubRabbitMQ\PubSubServiceProvider" --tag="pubsub-rabbitmq-subscriptions"
```

This is the contents of the published subscriptions file:

```php
return [
    //
];
```

## Usage

```php
$laravelPubSubRabbitMQ = new Hyperlab\LaravelPubSubRabbitMQ();
echo $laravelPubSubRabbitMQ->echoPhrase('Hello, world!');
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

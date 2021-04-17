<?php

namespace Hyperlab\LaravelPubSubRabbitMQ\Subscriber;

use Hyperlab\LaravelPubSubRabbitMQ\Tests\Log;
use Hyperlab\LaravelPubSubRabbitMQ\Tests\Subscribers\HandleUserCreated;
use Hyperlab\LaravelPubSubRabbitMQ\Tests\Subscribers\HandleUserDeleted;
use Hyperlab\LaravelPubSubRabbitMQ\Tests\TestCase;

class SubscriberTest extends TestCase
{
    /** @test */
    public function it_can_handle_different_types_of_subscribers()
    {
        $user = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
        ];

        $createdMessage = new Message('user.created', $user);
        $deletedMessage = new Message('user.deleted', $user);

        Subscriber::new(HandleUserCreated::class)->handle($createdMessage);
        Subscriber::new(HandleUserDeleted::class)->handle($deletedMessage);
        Subscriber::new([HandleUserDeleted::class, 'handle'])->handle($deletedMessage);

        $this->assertEquals([
            "Welcome, John!",
            "Goodbye email sent to john.doe@example.com",
            "Goodbye email sent to john.doe@example.com",
        ], Log::read());
    }
}

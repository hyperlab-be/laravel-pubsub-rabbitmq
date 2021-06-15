<?php

namespace Hyperlab\LaravelPubSubRabbitMQ\Subscriber;

use Hyperlab\LaravelPubSubRabbitMQ\Tests\Log;
use Hyperlab\LaravelPubSubRabbitMQ\Tests\Subscribers\HandleUserCreated;
use Hyperlab\LaravelPubSubRabbitMQ\Tests\Subscribers\HandleUserDeleted;
use Hyperlab\LaravelPubSubRabbitMQ\Tests\TestCase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class SubscriberTest extends TestCase
{
    /** @test */
    public function it_can_handle_different_types_of_subscribers()
    {
        $user = MessagePayload::new([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
        ]);

        $createdMessage = new Message(Str::uuid()->toString(), 'user.created', $user, Carbon::now());
        $deletedMessage = new Message(Str::uuid()->toString(), 'user.deleted', $user, Carbon::now());

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

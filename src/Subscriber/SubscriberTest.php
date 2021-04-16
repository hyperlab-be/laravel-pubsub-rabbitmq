<?php

namespace Hyperlab\LaravelPubSubRabbitMQ\Subscriber;

use Hyperlab\LaravelPubSubRabbitMQ\Message;
use Hyperlab\LaravelPubSubRabbitMQ\Tests\Log;
use Hyperlab\LaravelPubSubRabbitMQ\Tests\TestCase;

class SubscriberTest extends TestCase
{
    /** @test */
    public function it_works()
    {
        $message = new Message('user.created', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
        ]);

        $subscriber = new Subscriber(GreetNewUser::class);
        $subscriber->handle($message);
        $subscriber->handle($message);
        $subscriber->handle($message);

        $this->assertEquals([
            "Welcome, John!",
            "Welcome, John!",
            "Welcome, John!",
        ], Log::read());
    }
}

class GreetNewUser
{
    public function __invoke(Message $message): void
    {
        $firstName = $message->getPayload()['first_name'];

        Log::write("Welcome, {$firstName}!");
    }
}

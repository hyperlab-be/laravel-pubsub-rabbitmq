<?php

namespace Hyperlab\LaravelPubSubRabbitMQ\Subscriber;

use Hyperlab\LaravelPubSubRabbitMQ\Tests\Subscribers\HandleUserCreated;
use Hyperlab\LaravelPubSubRabbitMQ\Tests\Subscribers\HandleUserDeleted;
use Hyperlab\LaravelPubSubRabbitMQ\Tests\TestCase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class SubscriptionsTest extends TestCase
{
    /** @test */
    public function it_can_correctly_load_the_subcriptions_file()
    {
        config()->set('pubsub.subscriptions', __DIR__.'/../Tests/subscriptions.php');

        $subscriptions = Subscriptions::new();

        $this->assertEquals(['user.created', 'user.deleted'], $subscriptions->getMessageTypes());

        $userCreatedMessage = new Message(Str::uuid()->toString(), 'user.created', MessagePayload::new(), Carbon::now());
        $subscriber = $subscriptions->findSubscriberForMessage($userCreatedMessage);
        $this->assertInstanceOf(Subscriber::class, $subscriber);
        $this->assertEquals(HandleUserCreated::class, $subscriber->getClassName());
        $this->assertEquals('__invoke', $subscriber->getMethodName());

        $userDeletedMessage = new Message(Str::uuid()->toString(), 'user.deleted', MessagePayload::new(), Carbon::now());
        $subscriber = $subscriptions->findSubscriberForMessage($userDeletedMessage);
        $this->assertInstanceOf(Subscriber::class, $subscriber);
        $this->assertEquals(HandleUserDeleted::class, $subscriber->getClassName());
        $this->assertEquals('handle', $subscriber->getMethodName());

        $userUpdatedMessage = new Message(Str::uuid()->toString(), 'user.updated', MessagePayload::new(), Carbon::now());
        $subscriber = $subscriptions->findSubscriberForMessage($userUpdatedMessage);
        $this->assertNull($subscriber);
    }
}

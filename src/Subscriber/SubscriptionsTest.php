<?php

namespace Hyperlab\LaravelPubSubRabbitMQ\Subscriber;

use Hyperlab\LaravelPubSubRabbitMQ\Tests\Subscribers\HandleUserCreated;
use Hyperlab\LaravelPubSubRabbitMQ\Tests\Subscribers\HandleUserDeleted;
use Hyperlab\LaravelPubSubRabbitMQ\Tests\TestCase;

class SubscriptionsTest extends TestCase
{
    /** @test */
    public function it_can_correctly_load_the_subcriptions_file()
    {
        config()->set('pubsub.subscriptions', __DIR__.'/../Tests/subscriptions.php');

        $subscriptions = Subscriptions::new();

        $this->assertEquals(['user.created', 'user.deleted'], $subscriptions->getMessageTypes());

        $subscriber = $subscriptions->findSubscriberForMessageType('user.created');
        $this->assertInstanceOf(Subscriber::class, $subscriber);
        $this->assertEquals(HandleUserCreated::class, $subscriber->getClassName());
        $this->assertEquals('__invoke', $subscriber->getMethodName());

        $subscriber = $subscriptions->findSubscriberForMessageType('user.deleted');
        $this->assertInstanceOf(Subscriber::class, $subscriber);
        $this->assertEquals(HandleUserDeleted::class, $subscriber->getClassName());
        $this->assertEquals('handle', $subscriber->getMethodName());

        $subscriber = $subscriptions->findSubscriberForMessageType('user.updated');
        $this->assertNull($subscriber);
    }
}

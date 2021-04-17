<?php

namespace Hyperlab\LaravelPubSubRabbitMQ\Subscriber;

use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;

class Subscriber
{
    private string | array $subscriber;

    public function __construct(string | array $subscriber)
    {
        $this->subscriber = $subscriber;
    }

    public static function new(string | array $subscriber): static
    {
        return new static($subscriber);
    }

    public function handle(Message $message): void
    {
        $className = $this->getClassName();
        $methodName = $this->getMethodName();

        app($className)->{$methodName}($message);
    }

    public function getClassName(): string
    {
        if (is_array($this->subscriber)) {
            return $this->subscriber[0];
        }

        return $this->subscriber;
    }

    public function getMethodName(): string
    {
        if (is_array($this->subscriber) && array_key_exists(1, $this->subscriber)) {
            return $this->subscriber[1];
        }

        return $this->guessMethodName();
    }

    private function guessMethodName(): string
    {
        $className = $this->getClassName();
        $publicMethods = (new ReflectionClass($className))->getMethods(ReflectionMethod::IS_PUBLIC);

        $method = collect($publicMethods)->first(function (ReflectionMethod $method): bool {
            $firstParameter = $method->getParameters()[0] ?? null;

            if (! $firstParameter) {
                return false;
            }

            $type = $firstParameter->getType();

            if (! $type instanceof ReflectionNamedType) {
                return false;
            }

            return $type->getName() === Message::class;
        });

        if ($method === null) {
            throw new \Exception("Could not find a message handler method in Subscriber '{$className}'.");
        }

        return $method->getName();
    }
}

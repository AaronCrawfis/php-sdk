<?php

require_once __DIR__.'/Mocks/DaprClient.php';

use Dapr\Actors\ActorRuntime;
use Dapr\Serializer;
use PHPUnit\Framework\TestCase;

abstract class DaprTests extends TestCase
{
    public function setUp(): void
    {
        \Dapr\DaprClient::$responses = [];
        // reset other static objects
        $class = new ReflectionClass(\Dapr\PubSub\Subscribe::class);
        $class->setStaticPropertyValue('subscribed_topics', []);
        $class->setStaticPropertyValue('handlers', []);

        $class = new ReflectionClass(ActorRuntime::class);
        $class->setStaticPropertyValue('actors', []);
        $class->setStaticPropertyValue('config', []);

        $class = new ReflectionClass(\Dapr\Binding::class);
        $class->setStaticPropertyValue('bindings', []);
    }

    protected function deserialize(string $json)
    {
        return \Dapr\Deserializer::maybe_deserialize(json_decode($json, true));
    }

    protected function set_body($data)
    {
        ActorRuntime::$input = tempnam(sys_get_temp_dir(), uniqid());
        file_put_contents(ActorRuntime::$input, json_encode(Serializer::as_json($data)));
    }
}
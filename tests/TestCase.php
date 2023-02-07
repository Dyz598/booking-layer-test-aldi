<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use ReflectionClass;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function runInaccessibleMethod($object, string $method, ...$args)
    {
        $reflection = new ReflectionClass($object);
        $method = $reflection->getMethod($method);
        $method->setAccessible(true);

        return $method->invoke($object, $args);
    }

    public function getInaccessibleProperty($object, string $method)
    {
        $reflection = new ReflectionClass($object);
        $property = $reflection->getProperty($method);
        $property->setAccessible(true);

        return $property->getValue($object);
    }
}

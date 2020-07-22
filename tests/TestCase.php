<?php

namespace Mehradsadeghi\DecoratorTest;

use Mehradsadeghi\Decorator\DecoratorServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [DecoratorServiceProvider::class];
    }
}
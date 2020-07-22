<?php

namespace Mehradsadeghi\Decorator;

use Illuminate\Support\Facades\Facade;

/**
 * Class DecoratorFacade
 */
class DecoratorFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return static::class;
    }
}

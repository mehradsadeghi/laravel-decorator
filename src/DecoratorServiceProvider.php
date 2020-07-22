<?php

namespace Mehradsadeghi\Decorator;

use Illuminate\Support\ServiceProvider;

class DecoratorServiceProvider extends ServiceProvider {

    public function register()
    {
        app()->singleton(Decorator::class);
    }
}

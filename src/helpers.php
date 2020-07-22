<?php

use Mehradsadeghi\Decorator\Decorator;

if (!function_exists('decorator')) {
    function decorator($callable = null)
    {
        $decorator = app(Decorator::class);

        if (!is_null($callable)) {
            $decorator->register($callable);
        }

        return $decorator;
    }
}

if (!function_exists('decorate')) {
    function decorate($callable, $params)
    {
        return app(Decorator::class)->decorate($callable, $params);
    }
}

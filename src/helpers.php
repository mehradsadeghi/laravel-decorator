<?php

use Mehradsadeghi\Decorator\Decorator;

if (!function_exists('decorator')) {
    function decorator($callable = null)
    {
        $decorator = app(Decorator::class);

        if (!is_null($callable)) {
            $decorator->setCurrentCallable($callable);
        }

        return $decorator;
    }
}

if (!function_exists('decorate')) {
    function decorate($callable)
    {
        return app(Decorator::class)->register($callable);
    }
}

if (!function_exists('decorateIt')) {
    function decorateIt($callable, $params)
    {
        return app(Decorator::class)->decorateIt($callable, $params);
    }
}

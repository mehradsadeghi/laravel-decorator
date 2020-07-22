<?php

use Mehradsadeghi\Decorator\Decorator;

if (! function_exists('decorateWith')) {
    function decorateWith($callable, $decorator) {
        app(Decorator::class)->decorateWith($callable, $decorator);
    }
}

if (! function_exists('decorate')) {
    function decorate($callable, $params) {
        return app(Decorator::class)->decorate($callable, $params);
    }
}





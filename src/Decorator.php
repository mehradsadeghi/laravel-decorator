<?php

namespace Mehradsadeghi\Decorator;

use InvalidArgumentException;

class Decorator {

    private static $decorations = [];
    private static $currentCallable;

    public function register($callable)
    {
        $callable = $this->normalizeCallable($callable);

        self::$decorations[$callable] = [];
        self::$currentCallable = $callable;

        return $this;
    }

    public function with($decorator)
    {
        self::$decorations[self::$currentCallable][] = $decorator;
        return $this;
    }

    public function decorateIt($callable, array $params = [])
    {
        $callable = $this->normalizeCallable($callable);

        if(is_null($decorators = $this->getDecorations($callable))) {
            return app()->call($callable, $params);
        }

        $callable = $this->getFinalCallable($decorators, $callable);

        return app()->call($callable, $params);
    }

    private function getDecorations($callable)
    {
        return self::$decorations[$callable] ?? null;
    }

    private function normalizeCallable($callable)
    {
        $this->validateCallable($callable);

        return join('@', $callable);
    }

    private function getFinalCallable($decorators, $callable)
    {
        foreach ($decorators as $decorator) {
            $callable = app()->call($decorator, [$callable]);
        }
        return $callable;
    }

    private function validateCallable($callable)
    {
        if (!is_array($callable)) {
            throw new InvalidArgumentException('callable should be an array of class and method.');
        }
    }
}
